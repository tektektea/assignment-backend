<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialAllocationItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_allocation_items', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer("material_id")->nullable(true);
            $table->string('material')->nullable(true);
            $table->integer('quantity')->default(1);
            $table->bigInteger('allocation_id')->unsigned()->nullable(true);

            $table->foreign('allocation_id')
                ->references('id')->on('material_allocations')
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
        Schema::dropIfExists('material_allocation_items');
    }
}
