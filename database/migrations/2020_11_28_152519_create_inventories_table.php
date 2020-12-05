<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->bigInteger("material_id")->unsigned()->nullable(false);
            $table->string("material_name")->nullable(true);
            $table->integer('quantity')->default(0);
            $table->date('voucher_date')->default(\Carbon\Carbon::now());
            $table->string('created_by')->nullable(false);
            $table->string('remark');
            $table->timestamps();

//            $table->foreign('material_id')
//                ->references('id')->on('materials')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
