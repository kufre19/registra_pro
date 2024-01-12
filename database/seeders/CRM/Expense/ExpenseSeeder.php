<?php

namespace Database\Seeders\CRM\Expense;

use App\Models\CRM\Expense\Expense;
use App\Models\CRM\Expense\ExpenseArea;
use Database\Seeders\Traits\DisableForeignKeys;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    use DisableForeignKeys;
    public function run()
    {
        $this->disableForeignKeys();
        Expense::query()->truncate();

        $faker = \Faker\Factory::create();

        $expense_areas = ExpenseArea::query()->get();
        foreach($expense_areas as $expense_area) {
            $expense_area->expenses()->create([
                'name' => $faker->word,
                'created_by' => 1,
                'amount' => $faker->numberBetween(100, 300),
                'description' => $faker->paragraph,
                'expense_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
            ]);
        }
    }
}
