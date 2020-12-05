<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllocationOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('allocation_order_items', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer('material_id');
            $table->string('material');
            $table->integer('quantity');
            $table->bigInteger('allocation_order_id')->unsigned()->nullable(true);

            $table->foreign('allocation_order_id')
                ->references('id')->on('allocation_orders')
                ->onDelete('cascade');
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
        Schema::dropIfExists('allocation_order_items');
    }
}
