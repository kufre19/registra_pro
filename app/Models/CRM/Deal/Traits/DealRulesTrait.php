<?php

namespace App\Models\CRM\Deal\Traits;

use Illuminate\Validation\Rule;

trait DealRulesTrait
{
    public function createdRules()
    {
        return [

            'title' => 'required|max:191',
            'value' => 'nullable|numeric',
            'contextable_id' => 'required',
            'pipeline_id' => [
                'required',
                Rule::exists('pipelines', 'id')
            ],
            'stage_id' => [
                'required',
                Rule::exists('stages', 'id')
            ]
        ];
    }
    public function updatedRules()
    {
        return [
            'title' => 'required|max:191',
            'value' => 'nullable|numeric',
            'expired_at' => 'nullable|date',
            'contextable_id' => [
                'required',
                Rule::exists(request('lead_type') == 1 ? 'people' : 'organizations', 'id')
            ],
            'pipeline_id' => [
                'required',
                Rule::exists('pipelines', 'id')
            ],
            'stage_id' => [
                'required',
                Rule::exists('stages', 'id')
            ]
        ];
    }
}
