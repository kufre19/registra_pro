<?php

    namespace App\Http\Controllers\CRM\Contact;

    use App\Filters\CRM\PersonFilter;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\CRM\Contact\ContactFormRequest;
    use App\Http\Requests\CRM\Import\ImportPersonRequest;
    use App\Http\Requests\CRM\Person\FileRequest;
    use App\Http\Requests\CRM\Person\FollowerPersonRequest;
    use App\Http\Requests\CRM\Person\PersonRequest as Request;
    use App\Mail\CRM\SendPersonCustomMail;
    use App\Models\Core\Status;
    use App\Models\CRM\Import\PersonImport;
    use App\Models\CRM\Organization\Organization;
    use App\Models\CRM\Person\Person;
    use App\Services\CRM\Activity\ActivityService;
    use App\Services\CRM\Contact\PersonService;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\HeadingRowImport;

class PersonController extends Controller
{
    public function __construct(PersonService $person, PersonFilter $personFilter)
    {
        $this->service = $person;
        $this->filter = $personFilter;
    }
        public function index()
        {
            if (\Request::exists('all')) {
                return $this->service
                    ->with(['organizations'])
                    ->filters($this->filter)
                    ->select('id', 'name')
                    ->get();
            }

            // $order = Str::contains(\Request::get('has_last_note'), ['asc', 'desc'])
            //     ? \Request::get('orderBy')
            //     : 'desc';

            $orderBy =  \Request::get('has_last_note');
            if ($orderBy != 'asc' && $orderBy != 'desc') {
                $orderBy = 'desc';
            }
            if (\Request::exists('has_last_note')) {

                $orderBy =  \Request::get('has_last_note');
                if($orderBy == "oldest")
                {
                    $orderBy = "asc";
                }else{
                    $orderBy = "desc";
                }

                $ser = $this->service
                ->showAll($orderBy)
                ->filters($this->filter)
                // ->orderBy('created_at', 'desc')
                ->paginate(
                    request(
                        'per_page',
                        \Request::get('per_page') ?? 15
                    )
                );
                return $ser;
            }
            

            $ser = $this->service
            ->showAll()
            ->filters($this->filter)
            // ->orderBy('created_at', 'desc')
            ->paginate(
                request(
                    'per_page',
                    \Request::get('per_page') ?? 15
                )
            );
            return $ser;
           
        }

    public function store(Request $request)
    {
        return $this->service->savePerson($request);
    }

    public function show($id)
    {
        return $this->service->showPerson($id, 'organizations');
    }

    public function edit($id)
    {
        return view('crm.contacts.person-details', compact('id'));
    }

    public function update(Request $request, Person $person)
    {
        $this->service
            ->setAttributes(
                $request->only(
                    'name',
                    'address',
                    'country_id',
                    'city',
                    'state',
                    'zip_code',
                    'area',
                    'contact_type_id',
                    'owner_id',
                    'phone',
                    'email',
                    'organizationData',
                    'customs'
                )
            )
            ->setModel($person)
            ->update()
            ->when($request->has('phone'), fn (PersonService $service) => $service->syncPhone())
            ->when($request->has('email'), fn (PersonService $service) => $service->syncEmail())
            ->when($request->has('organizationData'), fn (PersonService $service) => $service->syncOrganization());

        if ($request->customs) {
            $this->service->customFieldSync($request->customs, $person, $this->service);
        }
        return updated_responses('person');
    }

    public function destroy(Person $person)
    {
        $this->service
                ->setModel($person)
                ->deleteCustomFiled()
                ->delete();

        return deleted_responses('person');
    }

        public function attachTag(\Illuminate\Http\Request $request, Person $person)
        {
            $person->tags()->attach($request->tag_id);
            return updated_responses('person');
        }

        public function detachTag(\Illuminate\Http\Request $request, Person $person)
        {
            $person->tags()->detach($request->tag_id);
            return updated_responses('person');
        }

        public function personFollower(FollowerPersonRequest $request, Person $person)
        {
            if ($request->has('person_id')) {
                $data = $this->service->prepareFollowersDataBeforeSync($request['person_id']);
                $this->service->followerSyncAll($person->followers(), $data);
            }
            return updated_responses('synchronization');
        }

        public function personContactSync(ContactFormRequest $request, Person $person)
        {
            if ($request->has('phone')) {
                $this->service->syncAll($person->phone(), $request['phone']);
            }

            if ($request->has('email')) {
                $this->service->syncAll($person->email(), $request['email']);
            }

            return updated_responses('synchronization');
        }

        public function organizationJobTitleSync(\Illuminate\Http\Request $request, Person $person)
        {
            validator($request->except('allowed_resource'), [
                '*.organization_id' => 'required',
            ], ['required' => 'The field is required.'])->validate();

            $person->organizations()->sync($request->except('allowed_resource'));
            return updated_responses('synchronization');
        }

        public function profilePicture(\Illuminate\Http\Request $request, Person $person)
        {
            if ($request->profile_picture) {
                $this->service->profilePicture($request->profile_picture, $person);
            }
            return updated_responses('profile_picture');
        }

        public function personActivities(Person $person)
        {
            return $person->activity()
                ->with([
                    'participants',
                    'collaborators',
                ])
                ->filters($this->filter)
                ->get();
        }

        public function personNotes(Person $person)
        {
            return $person
                ->notes()
                ->filters($this->filter)
                ->get();
        }

        public function personFiles(Person $person)
        {
            return $person
                ->files()
                ->where('type', '!=', 'profile_picture')
                ->filters($this->filter)
                ->get();
        }

        public function importPerson(ImportPersonRequest $request)
        {
            // get current maximum execution time value
            $current_execution_time = ini_get('max_execution_time');

            // maximum execution time is to set 300s
            ini_set('max_execution_time', 300);

            //get current $memory_limit
            $current_memory_limit = ini_get('memory_limit');

            //set memory limit to 512M
            ini_set('memory_limit', '512M');

            $file = $request->file('import_file');

            $import = new PersonImport;
            $headings = (new HeadingRowImport)->toArray($file);

            $missingField = array_diff($import->requiredHeading, $headings[0][0]);
            if (count($missingField) > 0) {
                return response(collect($missingField)->values(), 423);
            }
            $import->import($file);
            $failures = $import->failures();
            // after import action complete
            // set to previous maximum execution time value
            ini_set('max_execution_time', $current_execution_time);
            //set its previous state of memory limit
            ini_set('memory_limit', $current_memory_limit);
            //partial import
            if ($failures->count() > 0) {
                $stat = import_failed($file, $failures);
                return [
                    'status' => 200,
                    'message' => trans('default.person') . ' ' . trans('default.partially_imported'),
                    'stat' => $stat
                ];
            }
            return [
                'status' => 200,
                'message' => trans('default.person') . ' ' . trans('default.has_been_imported_successfully')
            ];
        }

        public function personActivitiesSync(\Illuminate\Http\Request $request, Person $person)
        {
            $request->validate([
                'activity_type_id' => 'required',
                'title' => 'required',
                'started_at' => 'nullable|date',
                'ended_at' => 'nullable|date',
                'start_time' => 'nullable|date_format:H:i',
                'end_time' => 'nullable|date_format:H:i',
                'reminder_type' => 'nullable',
                'reminder_on' => 'nullable|required_if:reminder_type,==,custom|date',
            ]);

            if (!$request->status_id) {
                $todo = Status::where('name', 'LIKE', '%todo')->first()->id;
                $request['status_id'] = $todo;
            }

            $options = request()->all();
            $options['reminder_on'] = resolve(ActivityService::class)->getReminderOn();
            $activity = $person->activity()->create($options);

            if ($request->person_id) {
                $activity->participants()->sync($request->person_id);
            }

            if ($request->owner_id) {
                $activity->collaborators()->sync($request->owner_id);
            }

            resolve(ActivityService::class)->setModel($activity)->notifyToRecipients();

            return created_responses('activity');
        }

        public function personNoteSync(\Illuminate\Http\Request $request, Person $person)
        {
            $person->notes()->create($request->all());

            return created_responses('note');
        }

        public function personFileSync(FileRequest $request, Person $person)
        {
            $this->service->fileSync($request->path, $person);
            return response()->json([
                'status' => 'true',
                'message' => trans('default.file_has_been_uploaded_successfully')
            ]);
        }

    public function personFollowers(Person $person)
    {
        $followers = $person->load(['followers.person' => function ($person) {
            $person
            ->withCount(['openDeals', 'closeDeals', ])
            ->with(['contactType:id,name,class', 'owner:id,first_name,last_name', 'tags:id,name,color_code']);
        }])->followers->pluck('person')->flatten()->values();

        return $this->service->paginate($followers, request('per_page', 15), request('page', 1));
    }

    public function personDealsCounter()
    {
        return Person::withCount(['deals', 'openDeals', 'closeDeals'])->get();
    }

    public function leadUserInfo()
    {
        return Person::with([
            'email' => function ($q) {
                $q->select(
                    'value',
                    'type_id',
                    'contextable_type',
                    'contextable_id'
                )
                    ->with([
                        'type:id,name,class'
                    ]);
            },
        ])->where('attach_login_user_id', auth()->user()->id)
            ->select('id', 'name')
            ->first();
    }

    public function personBulkDelete(\Illuminate\Http\Request $request)
    {
        if($request->is_all_selected) Person::query()->delete();
        else Person::whereIn('id', $request->deletable_ids)->delete();
        return deleted_responses('person');
    }

    public function bulkAttachTags(\Illuminate\Http\Request $request)
    {
        $persons = Person::whereIn('id', $request->attachable_ids)->get();
        $tagId = $request->tag_id;
        foreach ($persons as $person) {
            $person->tags()->attach($tagId);
        }
        return updated_responses('person');
    }

    public function bulkDetachTags(\Illuminate\Http\Request $request)
    {
        $persons = Person::whereIn('id', $request->detachable_ids)->get();
        $tagId = $request->tag_id;
        foreach ($persons as $person) {
            $person->tags()->detach($tagId);
        }
        return updated_responses('person');
    }

    public function bulkAttachOrganizations(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'attachable_ids' => 'required|array',
            'organization.organization_id' => 'required'
        ]);
        if($request->is_all_selected) $persons = Person::all();
        else $persons = Person::whereIn('id', $request->attachable_ids)->get();
        $organization = $request->organization;
        $organizationPersonIds =
            Organization::where('id', $organization['organization_id'])->first()->persons()->pluck('id')->toArray();
        foreach ($persons as $person) {
            if(!in_array($person['id'], $organizationPersonIds)) {
                $person->organizations()->attach([
                    $organization['organization_id'] => ['job_title'=> $organization['job_title']]
                ]);
            }
        }
        return updated_responses('person');
    }

    public function bulkUpdateLeadGroup(\Illuminate\Http\Request $request) {
        $request->validate([
            'attachable_ids' => 'required|array',
            'contact_type_id' => 'required'
        ]);
        if($request->is_all_selected) Person::query()->update(['contact_type_id'=> $request->contact_type_id]);
        else Person::whereIn('id', $request->attachable_ids)->update(['contact_type_id'=> $request->contact_type_id]);
        return updated_responses('person');
    }

    public function bulkUpdateOwner(\Illuminate\Http\Request $request) {
        $request->validate([
            'attachable_ids' => 'required|array',
            'owner_id' => 'required'
        ]);

        if($request->is_all_selected) Person::query()->update(['owner_id'=> $request->owner_id]);
        else Person::whereIn('id', $request->attachable_ids)->update(['owner_id'=> $request->owner_id]);
        return updated_responses('person');
    }

    public function sendEmailToPerson(Person $person, \Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string',
            'mail' => 'required|string',
        ]);

        Mail::to($request->email)
            ->send(new SendPersonCustomMail($request));

        return custom_response('email_sent_successfully');
    }
}
