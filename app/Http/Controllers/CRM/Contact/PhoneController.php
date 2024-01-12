<?php

namespace App\Http\Controllers\CRM\Contact;

use App\Http\Controllers\Controller;
use App\Models\CRM\Phone\Phone;
use Illuminate\Http\Request;

class PhoneController extends Controller
{

    public function index()
    {
        return Phone::select('id', 'value')->get();
    }

    public function searchPhoneLists(Request $request)
    {
        return Phone::query()
        ->where('value', 'like', '%'.$request->phone.'%')
        ->select('id', 'value')
        ->paginate(
            request(
                'per_page',
                \Request::get('per_page') ?? 10
            )
        );
    }
}
