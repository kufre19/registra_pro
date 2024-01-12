<?php

    namespace App\Services\CRM\Contact;

    use App\Helpers\Core\Traits\FileHandler;
    use App\Helpers\Core\Traits\HasWhen;
    use App\Helpers\CRM\Traits\StoreFileTrait;
    use App\Models\CRM\Contact\ContactType;
    use App\Models\CRM\Person\Person;
    use App\Models\CRM\User\User;
    use App\Services\ApplicationBaseService;
    use App\Services\CRM\Traits\csvImportTrait;
    use App\Services\CRM\Traits\personOrganizationDetails;

    /**
     * @method paginate(array|\Illuminate\Http\Request|string $request)
     */
    class PersonService extends ApplicationBaseService
    {
        use personOrganizationDetails, csvImportTrait, FileHandler, StoreFileTrait, HasWhen;

        public function __construct(Person $person)
        {
            $this->model = $person;
        }

        public function showAll()
        {
            return $this
            ->withCount(['openDeals', 'closeDeals'])
            ->with([
                'contactType:id,name,class',
                'owner:id,first_name,last_name',
                'phone'  => function($q){
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
                'tags:id,name,color_code',
                'organizations:id,name,owner_id',
//                'customFields', //should ensure other field isn't use from different method
                'customFields'=>function($que){
                    $que->select(
                        'id',
                        'value',
                        'contextable_id',
                        'contextable_type',
                        'custom_field_id'
                    );
                },
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
                'country', //should ensure isn't use from different method before remove
                'profilePicture' //should ensure isn't use from different method before remove
            ]);
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        public function savePerson($request)
        {
            \DB::transaction(function () use ($request) {
                $hasPhone = (object)collect($request->phone)->first();
                $hasEmail = (object)collect($request->email)->first();
                $hasOrganization = (object)collect($request->organizationData)->first();

                $person = $this->save();

                if (!is_null($hasOrganization->organization_id)) {
                    $person->organizations()->sync($request->organizationData);
                }

                if (!is_null($hasPhone->value)) {
                    $this->syncAll($person->phone(), $request->phone);
                }

                if (!is_null($hasEmail->value)) {
                    $this->syncAll($person->email(), $request->email);
                }

                if ($request->customs) {
                    $this->customFieldSync($request->customs, $person, $this);
                }
            });

            return created_responses('person');
        }

        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        public function saveWebFormPerson($request)
        {
            \DB::transaction(function () use ($request) {
                $hasPhone = (object)collect($request->phone)->first();
                $hasEmail = (object)collect($request->email)->first();

                $options = array_merge([
                    'name' => request()->name,
                    'contact_type_id' => $this->getWebFormContactType()->id ?? null,
                    'owner_id' => $this->getWebFormOwner()->id ?? null,
                ], $request->only(
                    'address',
                    'country_id',
                    'city',
                    'state',
                    'zip_code',
                    'area'
                ));

                $person = $this->save($options);

                if (!is_null($hasPhone->value)) {
                    $this->syncAll($person->phone(), $request->phone);
                }

                if (!is_null($hasEmail->value)) {
                    $this->syncAll($person->email(), $request->email);
                }

                if ($request->customs) {
                    $this->customFieldSync($request->customs, $person, $this);
                }
            });

            return response()->json([
                'status' => true,
                'message' => trans('default.web_form_submitted'),
            ]);
        }

        public function getWebFormContactType() {
            return ContactType::firstOrCreate(['name' => 'Web', 'class' => 'success']);
        }

        public function getWebFormOwner() {
            return User::first();
        }

        public function showPerson($id, $pivot_data)
        {
            return $this->personOrganizationDetails($this->model, $id, $pivot_data);
        }

        public function fileSync($path, $person)
        {
            foreach ($path as $key => $value) {
                $file_path = $this->fileStore(
                    $value,
                    'person'
                );
                $person->files()->create([
                    'type' => 'person',
                    'path' => $file_path,
                ]);
            }
        }

        public function syncPhone()
        {
            $this->syncAll($this->model->phone(), $this->getAttribute('phone'));

            return $this;
        }

        public function syncEmail()
        {
            $this->syncAll($this->model->email(), $this->getAttribute('email'));

            return $this;
        }

        public function syncOrganization()
        {
            $this->model->organizations()->sync(
                collect($this->getAttribute('organizationData'))->filter(fn ($item) => $item['organization_id'])->each(fn ($item) => $item)
            );

            return $this;
        }

        public function update()
        {
            $this->model->fill($this->getAttributes())->save();

            return $this;
        }
    }
