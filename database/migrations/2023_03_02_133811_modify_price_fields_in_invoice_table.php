<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyPriceFieldsInInvoiceTable extends Migration

{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'price'))
            {
//                $table->double('price', 16, 2)->change();
                DB::statement('alter table invoices modify price DOUBLE(16,2) DEFAULT 0');
            }
            if (Schema::hasColumn('invoices', 'total'))
            {
//                $table->double('total', 16, 2)->change();
                DB::statement('alter table invoices modify total DOUBLE(16,2) DEFAULT 0');
            }
            if (Schema::hasColumn('invoices', 'discount_amount'))
            {
//                $table->double('discount_amount', 16, 2)->change();
                DB::statement('alter table invoices modify discount_amount DOUBLE(16,2) DEFAULT 0');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'price'))
            {
                $table->bigInteger('price')->change();
            }
            if (Schema::hasColumn('invoices', 'total'))
            {
                $table->bigInteger('total')->change();
            }
            if (Schema::hasColumn('invoices', 'discount_amount'))
            {
                $table->integer('discount_amount')->change();
            }
        });
    }
}
