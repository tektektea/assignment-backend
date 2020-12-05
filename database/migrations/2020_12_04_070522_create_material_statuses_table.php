<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_statuses', function (Blueprint $table) {
            $table->id();
            $table->enum('status',['idle','repairing','in-use'])->default('idle');
            $table->string('remark')->nullable(true);
            $table->bigInteger('material_id')->unsigned()->nullable(false);

            $table->foreign('material_id')->references('id')->on('materials')->cascadeOnDelete();
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
        Schema::dropIfExists('material_statuses');
    }
}
