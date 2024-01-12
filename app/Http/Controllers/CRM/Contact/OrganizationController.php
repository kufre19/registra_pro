<?php

    namespace App\Http\Controllers\CRM\Contact;

    use App\Filters\CRM\OrganizationFilter;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\CRM\Import\ImportOrganizationRequest;
    use App\Http\Requests\CRM\Organization\OrganizationRequest as Request;
    use App\Http\Requests\CRM\Person\FileRequest;
    use App\Models\Core\Status;
    use App\Models\CRM\Import\OrganizationImport;
    use App\Models\CRM\Organization\Organization;
    use App\Models\CRM\Person\Person;
    use App\Repositories\Core\Status\StatusRepository;
    use App\Services\CRM\Activity\ActivityService;
    use App\Services\CRM\Contact\OrganizationService;
    use Illuminate\Support\Str;
    use Maatwebsite\Excel\HeadingRowImport;

    class OrganizationController extends Controller
    {

        public function __construct(OrganizationService $organization, OrganizationFilter $organizationFilter)
        {
            $this->service = $organization;
            $this->filter = $organizationFilter;
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            if (\Request::exists('all')) {
                return $this->service
                    ->with(['persons'])
                    ->filters($this->filter)
                    ->select('id', 'name')
                    ->get();
            }

            $order = Str::contains(\Request::get('orderBy'), ['asc', 'desc'])
                ? \Request::get('orderBy')
                : 'desc';

            return $this->service
                ->showAll()
                ->orderBy('updated_at', $order)
                ->filters($this->filter)
                ->paginate(
                    request(
                        'per_page',
                        \Request::get('per_page') ?? 15
                    )
                );
        }

        public function searchOrganizationLists(Request $request)
        {
//            return $this->service
//                ->where('name', 'like', '%'.$request->name.'%')
//                ->select('id', 'name')
//                ->paginate(
//                    request(
//                        'per_page',
//                        \Request::get('per_page') ?? 10
//                    )
//                );

            $selected = request()->get('selected');

            return $this->service
                ->when($selected, function ($query) use ($selected) {
                    if (!request()->get('search')) {
                        $query->where('id', $selected);
                    }
                })
                ->filters($this->filter)
                ->select(['id', 'name'])
                ->paginate(request()->per_page ?? 15);
        }

        public function store(Request $request)
        {
            $organization = $this->service->save();

            if ($request->customs) {
                $this->service->customFieldSync($request->customs, $organization, $this->service);
            }

            return created_responses('organization');
        }

        public function show($id)
        {
            return $this->service->showOrganization($id, 'persons');
        }

        public function edit($id)
        {
            return view('crm.contacts.view', compact('id'));
        }

        public function update(Request $request, Organization $organization)
        {
            $organization->update($request->only(
                'name',
                'address',
                'contact_type_id',
                'owner_id',
                'country_id',
                'city',
                'state',
                'area',
                'zip_code'
            ));
            if ($request->customs) {
                $this->service->customFieldSync($request->customs, $organization, $this->service);
            }

            return updated_responses('organization');
        }

        public function destroy(Organization $organization)
        {
            $this->service
                ->setModel($organization)
                ->deleteCustomFiled()
                ->delete();

            return deleted_responses('organization');
        }


        public function attachTag(\Illuminate\Http\Request $request, Organization $organization)
        {
            $organization->tags()->attach($request->tag_id);

            return updated_responses('organization');
        }

        public function detachTag(\Illuminate\Http\Request $request, Organization $organization)
        {
            $organization->tags()->detach($request->tag_id);
            return updated_responses('organization');
        }

        /*
         * Request must have array object named data
         * And we are using route model binding here
         */
        public function personJobTitleSync(\Illuminate\Http\Request $request, Organization $organization)
        {
            validator($request->except('allowed_resource'), [
                '*.person_id' => 'required',
            ], ['required' => 'The field is required.'])->validate();

            $organization->persons()->sync($request->except('allowed_resource'));
            return updated_responses('synchronization');
        }

        public function profilePicture(\Illuminate\Http\Request $request, Organization $organization)
        {
            if ($request->profile_picture) {
                $this->service->profilePicture($request->profile_picture, $organization);
            }

            return created_responses('profile_picture');
        }

        public function organizationActivities(Organization $organization)
        {
            return $organization->activity()
                ->with([
                    'participants',
                    'collaborators',
                ])
                ->filters($this->filter)
                ->get();
        }

        public function orgNotes(Organization $organization)
        {
            return $organization
                ->notes()
                ->filters($this->filter)
                ->get();
        }

        public function orgFiles(Organization $organization)
        {
            return $organization
                ->files()
                ->where('type', '!=', 'profile_picture')
                ->filters($this->filter)
                ->get();
        }

        public function importOrganization(ImportOrganizationRequest $request)
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

            $import = new OrganizationImport();
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
                    'message' => trans('default.organizations') . ' ' . trans('default.partially_imported'),
                    'stat' => $stat
                ];
            }

            return [
                'status' => 200,
                'message' => trans('default.organizations') . ' ' . trans('default.has_been_imported_successfully')
            ];
        }

        public function organizationActivitiesSync(\Illuminate\Http\Request $request, Organization $organization)
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
            $activity = $organization->activity()->create($options);

            if ($request->person_id) {
                $activity->participants()->sync($request->person_id);
            }

            if ($request->owner_id) {
                $activity->collaborators()->sync($request->owner_id);
            }

            resolve(ActivityService::class)->setModel($activity)->notifyToRecipients();

            return created_responses('activity');
        }

        public function organizationNoteSync(\Illuminate\Http\Request $request, Organization $organization)
        {
            $organization->notes()->create($request->all());

            return created_responses('note');
        }

        public function organizationFileSync(FileRequest $request, Organization $organization)
        {
            $this->service->fileSync($request->path, $organization);

            return response()->json([
                'status' => 'true',
                'message' => trans('default.file_has_been_uploaded_successfully')
            ]);
        }

        public function exportOrganization()
        {
            $count = $this->service->count();
            $export_count = config('excel.exports.chunk_size');
            if ($count >= $export_count) {
                return view('crm.export.export', [
                    'item_per_sheet' => $export_count,
                    'total_sheet_number' => $count / $export_count,
                    'url' => 'export/organization',
                    'title' => app_trans('default.download_organization_data')
                ]);
            }
            return $this->download(0);
        }

        public function download($skip = 0)
        {
            return $this->service->downloadOrganization($skip);
        }

        public function organizationBulkDelete(\Illuminate\Http\Request $request)
        {
            if($request->is_all_selected) Organization::query()->delete();
            else Organization::whereIn('id', $request->deletable_ids)->delete();
            return deleted_responses('organization');
        }

        public function bulkAttachTags(\Illuminate\Http\Request $request)
        {
            $organizations = Organization::whereIn('id', $request->attachable_ids)->get();
            $tagId = $request->tag_id;
            foreach ($organizations as $organization) {
                $organization->tags()->attach($tagId);
            }
            return updated_responses('organization');
        }

        public function bulkDetachTags(\Illuminate\Http\Request $request)
        {
            $organizations = Organization::whereIn('id', $request->detachable_ids)->get();
            $tagId = $request->tag_id;
            foreach ($organizations as $organization) {
                $organization->tags()->detach($tagId);
            }
            return updated_responses('organization');
        }

        public function bulkAttachPersons(\Illuminate\Http\Request $request)
        {
            $request->validate([
                'attachable_ids' => 'required|array',
                'person.person_id' => 'required'
            ]);
            if($request->is_all_selected) $organizations = Organization::all();
            else $organizations = Organization::whereIn('id', $request->attachable_ids)->get();
            $person = $request->person;
            $personOrganizationIds =
                Person::where('id', $person['person_id'])->first()->organizations()->pluck('id')->toArray();
            foreach ($organizations as $organization) {
                if(!in_array($organization['id'], $personOrganizationIds)) {
                    $organization->persons()->attach([
                        $person['person_id'] => ['job_title'=> $person['job_title']]
                    ]);
                }
            }
            return updated_responses('organization');
        }

        public function bulkUpdateLeadGroup(\Illuminate\Http\Request $request) {
            $request->validate([
                'attachable_ids' => 'required|array',
                'contact_type_id' => 'required'
            ]);

            if($request->is_all_selected) Organization::query()->update(['contact_type_id'=> $request->contact_type_id]);
            else Organization::whereIn('id', $request->attachable_ids)->update(['contact_type_id'=> $request->contact_type_id]);
            return updated_responses('organization');
        }

        public function bulkUpdateOwner(\Illuminate\Http\Request $request) {
            $request->validate([
                'attachable_ids' => 'required|array',
                'owner_id' => 'required'
            ]);

            if($request->is_all_selected) Organization::query()->update(['owner_id'=> $request->owner_id]);
            else Organization::whereIn('id', $request->attachable_ids)->update(['owner_id'=> $request->owner_id]);
            return updated_responses('organization');
        }

    }
