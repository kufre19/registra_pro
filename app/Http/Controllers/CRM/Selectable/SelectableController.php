<?php

namespace App\Http\Controllers\CRM\Selectable;

use App\Filters\Core\UserFilter;
use App\Filters\CRM\CountryFilter;
use App\Filters\CRM\DealFilter;
use App\Filters\CRM\OrganizationFilter;
use App\Filters\CRM\PersonFilter;
use App\Filters\CRM\TagFilter;
use App\Http\Controllers\Controller;
use App\Models\Core\Status;
use App\Models\CRM\Contact\PhoneEmailType;
use App\Models\CRM\Country\Country;
use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Expense\ExpenseArea;
use App\Models\CRM\Organization\Organization;
use App\Models\CRM\Person\Person;
use App\Models\CRM\Tag\Tag;
use App\Models\CRM\Tax\Tax;
use App\Models\CRM\User\User;
use App\Repositories\Core\Status\StatusRepository;
use Illuminate\Http\Request;

class SelectableController extends Controller
{

    public function selectableOrganisations()
    {
        $selected = request()->get('selected');

        return Organization::query()
            ->permission()
            ->with(['persons:id,name'])
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new OrganizationFilter())
            ->select(['id', 'name'])
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableTags()
    {
        $selected = request()->get('selected');

        return Tag::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new TagFilter())
            ->select('id', 'name', 'color_code')
            ->paginate(request()->per_page ?? 15);
    }


    public function selectablePersons()
    {
        $selected = request()->get('selected');

        return Person::query()
            ->permission()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new PersonFilter())
            ->with(['organizations'])
            ->select('id', 'name')
            ->paginate(request()->per_page ?? 15);
    }

    // Relational data is loaded according to previous api
    public function selectableDeals()
    {
        $selected = request()->get('selected');

        return Deal::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new DealFilter())
            ->where('status_id', resolve(StatusRepository::class)->dealOpen())
//            ->with([
//                'owner' => function ($query) {
//                    $query->select('id', 'first_name', 'last_name')
//                        ->with([
//                            'profilePicture',
//                        ]);
//                },
//                'contextable' => function ($query) {
//                    $query->select('id', 'name', 'address', 'contact_type_id', 'owner_id')
//                        ->with([
//                            'contactType:id,name,class', 'profilePicture',
//                            'email.type', 'phone.type',
//                        ]);
//                },
//                'contactPerson:id,name',
//                'contactPerson.email',
//                'lostReason:id,lost_reason'
//            ])
            ->select('id', 'title', 'created_at')
            ->paginate(request()->per_page ?? 15);
    }

    //crmAuthUsers
    public function selectableOwners()
    {
        //dd(\request()->all());
        $selected = request()->get('selected');

        return User::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->whereHas('roles', function ($query) {
                $query->where('name', '!=', 'Client');
            })
            ->whereNotNull(['first_name', 'last_name'])
            ->filters(new UserFilter())
            ->select('id', 'first_name', 'last_name')
            ->paginate(request()->per_page ?? 15);
    }


    public function selectableOrganizationPerson($organization_id = null)
    {
        $selected = request()->get('selected');

        return Person::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new PersonFilter())
            ->whereHas('organizations', function ($query) use ($organization_id){
                $query->where('id', $organization_id);
            })
//            ->with(['organizations'])
            ->select('id', 'name')
            ->paginate(request()->per_page ?? 15);
    }


    public function selectableCountries(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $selected = request()->get('selected');

        return Country::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new CountryFilter())
            ->select('id', 'code', 'name')
            ->paginate(request()->per_page ?? 15);
    }

    public function selectableTaxes()
    {
        $selected = request()->get('selected');

        return Tax::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new CountryFilter())
            ->select('id', 'name', 'value')
            ->paginate(request()->per_page ?? 15);
    }

    public function allTaxes()
    {
        return Tax::query()
            ->select('id', 'name', 'value')
            ->get();
    }


    public function selectableExpenseAreas()
    {
        $selected = request()->get('selected');

        return ExpenseArea::query()
            ->when($selected, function ($query) use ($selected) {
                if (!request()->get('search')) {
                    $query->where('id', $selected);
                }
            })
            ->filters(new CountryFilter())
            ->select('id', 'name')
            ->paginate(request()->per_page ?? 15);
    }


}
