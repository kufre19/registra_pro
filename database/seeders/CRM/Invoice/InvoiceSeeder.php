<?php

namespace Database\Seeders\CRM\Invoice;

use App\Models\CRM\Deal\Deal;
use App\Models\CRM\Invoice\Invoice;
use App\Models\CRM\Tax\Tax;
use App\Repositories\Core\Status\StatusRepository;
use Carbon\Carbon;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();
        Invoice::query()->truncate();
        $deal = Deal::query()->first();
        $tax = Tax::query()->first();

        $data = [
            [
                'invoice_number' => 123000,
                'quantity' => 10,
                'price' => $deal->value,
                'discount_type' => 'fixed',
                'discount_amount' => 100,
                'tax_id' =>  null,
                'total' => ($deal->value * 10) - 100, //((price * qty) - discount) + tax
                'issue_date' => Carbon::tomorrow(),
                'due_date' => Carbon::tomorrow(),
                'deal_id' => $deal->id,
                'status_id' => resolve(StatusRepository::class)->invoicePaid(),
                'owner_id' => $deal->owner_id ?? null,
                'created_by' =>  auth()->id() ?? 1,
                'note' => 'invoice note ',
                'created_at' => now(),
            ],
            [
                'invoice_number' => 153000,
                'quantity' => 2,
                'price' => $deal->value,
                'tax_id' =>  $tax->id,
                'total' => ( ($deal->value * 2) + ( ($deal->value * 2) * $tax->value) / 100) - 500,
                'discount_type' => 'fixed',
                'discount_amount' => 500,
                'issue_date' => Carbon::tomorrow(),
                'due_date' => Carbon::tomorrow(),
                'deal_id' => $deal->id,
                'status_id' => resolve(StatusRepository::class)->invoicePaid(),
                'owner_id' => $deal->owner_id ?? null,
                'created_by' =>  auth()->id() ?? 1,
                'note' => 'invoice note 3',
                'created_at' => now(),
            ],
            [
                'invoice_number' => 212322,
                'quantity' => 20,
                'price' => $deal->value,
                'tax_id' =>  null,
                'total' => ($deal->value * 20) - 0,
                'discount_type' => 'none',
                'discount_amount' => 0,
                'issue_date' => Carbon::tomorrow(),
                'due_date' => Carbon::tomorrow(),
                'deal_id' => $deal->id,
                'status_id' => resolve(StatusRepository::class)->invoicePaid(),
                'owner_id' => $deal->owner_id ?? null,
                'created_by' =>  auth()->id() ?? 1,
                'note' => 'invoice note 2',
                'created_at' => now(),
            ],
            [
                'invoice_number' => 354554,
                'quantity' => 2,
                'price' => $deal->value,
                'tax_id' => null,
                'total' => ($deal->value * 2) - 0, //((price * qty) - discount) + tax
                'discount_type' => 'none',
                'discount_amount' => 0,
                'issue_date' => Carbon::tomorrow(),
                'due_date' => Carbon::tomorrow(),
                'deal_id' => $deal->id,
                'status_id' => resolve(StatusRepository::class)->invoiceUnpaid(),
                'owner_id' => $deal->owner_id ?? null,
                'created_by' =>  auth()->id() ?? 1,
                'note' => 'invoice note 3',
                'created_at' => now(),
            ],
        ];

        Invoice::query()->insert($data);

        $this->enableForeignKeys();
    }
}
