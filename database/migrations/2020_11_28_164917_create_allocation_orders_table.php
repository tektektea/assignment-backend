<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocation_orders', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string('order_no')->nullable(false);
            $table->integer('from')->nullable(true);
            $table->integer('to')->nullable(true);
            $table->string('created_by')->nullable(true);
            $table->string('voucher_date');
            $table->string('remark')->nullable(true);
            $table->enum('status',['CREATED','REJECT','ACCEPT','DONE'])->default('CREATED');
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
        Schema::dropIfExists('allocation_orders');
    }
}
