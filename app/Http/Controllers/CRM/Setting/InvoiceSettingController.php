<?php

namespace App\Http\Controllers\CRM\Setting;

use App\Http\Controllers\Controller;
use App\Models\CRM\Invoice\Invoice;
use App\Repositories\Core\Setting\SettingRepository;
use App\Services\Core\Setting\SettingService;
use Illuminate\Http\Request;


class InvoiceSettingController extends Controller
{
    public function __construct(SettingService $service)
    {
        $this->service = $service;
    }

    public function getInvoiceSetting(SettingRepository $settings)
    {
       return $settings
           ->whereIn('name',['invoice_prefix','invoice_starting_number'])
           ->select('id','name','value','context')->get();
    }



    public function update(Request $request)
    {
        validator($request->all(), [
            'invoice_starting_number' => 'required|int',
            'invoice_prefix' => 'required',
        ])->validate();

        $check = Invoice::count();
        if ($check > 0) {
            return response()->json([
                'status' => false,
                'message' => __t('invoice_number_already_in_use')
            ], 200);
        }

        $this->service->update();
        return updated_responses('settings', [
            'settings' => $this->service->getFormattedSettings()
        ]);
    }
}
