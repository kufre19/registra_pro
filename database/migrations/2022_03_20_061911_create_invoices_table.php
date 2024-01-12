<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->text('invoice_number')->nullable();

            $table->integer('quantity', )->default(1);
            $table->bigInteger('price');
            $table->bigInteger('total', )->default(0);
            $table->string('discount_type')->nullable();
            $table->integer('discount_amount')->default(0);

            $table->dateTime('issue_date')->nullable();
            $table->dateTime('due_date')->nullable();

            $table->foreignId('deal_id')
                ->nullable()
                ->references('id')
                ->on('deals')
                ->onDelete('cascade');

            $table->foreignId('status_id')
                ->nullable()
                ->references('id')
                ->on('statuses')
                ->onDelete('SET NULL');

            $table->foreignId('owner_id')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->foreignId('created_by')
                ->nullable()
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->longText('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
