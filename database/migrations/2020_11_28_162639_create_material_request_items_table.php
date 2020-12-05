<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_request_items', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->integer('material_id')->nullable(true);
            $table->string('material')->nullable(true);
            $table->integer('quantity')->default(1);
            $table->bigInteger('request_id')->unsigned()->nullable(false);
            $table->timestamps();

            $table->foreign('request_id')
                ->references('id')->on('material_requests')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('material_request_items');
    }
}
