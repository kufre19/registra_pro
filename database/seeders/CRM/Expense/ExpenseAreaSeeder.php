<?php

namespace Database\Seeders\CRM\Expense;

use App\Models\CRM\Expense\ExpenseArea;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class ExpenseAreaSeeder extends Seeder
{
    use DisableForeignKeys;
    public function run()
    {
        $this->disableForeignKeys();
        ExpenseArea::query()->truncate();

        ExpenseArea::query()->insert([
            [
                'name' => 'Housing',
                'description' => 'Housing',
                "created_by" => 1,
            ],
            [
                'name' => 'Transportation',
                'description' => 'Transportation',
                "created_by" => 1,
            ],
            [
                'name' => 'Food',
                'description' => 'Food',
                "created_by" => 1,
            ],
            [
                'name' => 'Health',
                'description' => 'Health',
                "created_by" => 1,
            ],
            [
                'name' => 'Education',
                'description' => 'Education',
                "created_by" => 1,
            ],
            [
                'name' => 'Entertainment',
                'description' => 'Entertainment',
                "created_by" => 1,
            ],
            [
                'name' => 'Travel',
                'description' => 'Travel',
                "created_by" => 1,
            ],
            [
                'name' => 'Miscellaneous',
                'description' => 'Miscellaneous',
                "created_by" => 1,
            ],
            [
                'name' => 'Personal care',
                'description' => 'Personal care',
                "created_by" => 1,
            ],
        ]);
    }
}
