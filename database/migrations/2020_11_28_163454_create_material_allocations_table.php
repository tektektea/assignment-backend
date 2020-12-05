<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_allocations', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->uuid('allocation_no');
            $table->date('voucher_date');
            $table->integer('from');
            $table->integer('to');
            $table->string('created_by');
            $table->enum('allocation_type',['INVENTORY','DEPARTMENT']);
            $table->string('remark')->nullable(true);

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
        Schema::dropIfExists('material_allocations');
    }
}
