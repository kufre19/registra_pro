<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Person\WebFormPersonRequest as Request;
use App\Models\Core\Builder\Form\CustomField;
use App\Models\CRM\Contact\PhoneEmailType;
use App\Models\CRM\Country\Country;
use App\Services\CRM\Contact\PersonService;

class LeadWebFormController extends Controller
{
    public function __construct(PersonService $person)
    {
        $this->service = $person;
    }

    public function webFormCustomFields()
    {
        return CustomField::with(['customFieldType'])
            ->where('context', 'LIKE', '%person%')
            ->get();
    }

    public function leadWebForm()
    {
        return view('crm.webform.lead');
    }

    public function store(Request $request)
    {
        return $this->service->saveWebFormPerson($request);
    }

    public function phoneEmailTypes()
    {
        return PhoneEmailType::query()
            ->select('id', 'name')
            ->paginate($request->per_page ?? 10);
    }

    public function countries()
    {
        return Country::query()
            ->select('id', 'code', 'name')
            ->get();
    }
}
