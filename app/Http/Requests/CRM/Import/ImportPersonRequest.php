<?php

namespace App\Http\Requests\CRM\Import;

use Illuminate\Foundation\Http\FormRequest;

class ImportPersonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'import_file' => 'file|mimes:csv,txt|required'
        ];
    }
}
