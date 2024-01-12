<?php

namespace App\Http\Requests\CRM\Person;

use App\Http\Requests\BaseRequest;

class WebFormPersonRequest extends BaseRequest
{
    public function rules() : array
    {
        return [
            'name' => 'required|max:255',
        ];
    }

    public function messages()
    {
        return [];
    }
}
