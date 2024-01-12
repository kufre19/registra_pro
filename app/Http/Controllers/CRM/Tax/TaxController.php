<?php

namespace App\Http\Controllers\CRM\Tax;

use App\Filters\CRM\TaxFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CRM\Tax\TaxRequest;
use App\Models\CRM\Invoice\Invoice;
use App\Models\CRM\Tag\Taggable;
use App\Models\CRM\Tax\Tax;
use App\Services\CRM\Tax\TaxService;

class TaxController extends Controller
{
    public function __construct(TaxService $service, TaxFilter $taxFilter)
    {
        $this->service = $service;
        $this->filter = $taxFilter;
    }

    public function index()
    {
        return $this->service
            ->filters($this->filter)
            ->select('id', 'name', 'value')
            ->paginate(request('per_page', 15));
    }

    public function store(TaxRequest $request, Tax $tax)
    {
        $this->service->save();
        return created_responses('tax');
    }


    public function show(Tax $tax): Tax
    {
        return $tax;
    }

    public function update(TaxRequest $request, Tax $tax)
    {
        if (Invoice::query()->where('tax_id', $tax->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => __t('tax_already_in_use')
            ], 200);
        }

        $tax->update($request->all());
        return updated_responses('tax');
    }


    public function destroy(Tax $tax)
    {
        if (Invoice::query()->where('tax_id', $tax->id)->exists()) {
            return response()->json([
                'status' => false,
                'message' => __t('tax_already_in_use')
            ], 200);

        }else{
            if ($tax->delete()){
                return deleted_responses('tax');
            }
        }
    }
}
