<?php

namespace Database\Seeders\CRM\Tax;

use App\Models\CRM\Tax\Tax;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run()
    {
        $this->disableForeignKeys();
        Tax::query()->truncate();

        Tax::query()->insert([
            [
                'name' => '5% tax',
                'value' => 5,
            ],
            [
                'name' => '10% tax',
                'value' => 10,
            ],
            [
                'name' => '15% tax',
                'value' => 15,
            ],
        ]);
    }
}
