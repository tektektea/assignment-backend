<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_requests', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("request_id");
            $table->date("voucher_date")->default(\Carbon\Carbon::now());
            $table->integer('department_id')->nullable(false);
            $table->enum('status', ['REQUEST','PROCESSING','PARTIALLY_DONE', 'DONE'])->default('REQUEST');
            $table->string('remark')->nullable(true);
            $table->string('created_by')->nullable(true);
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
        Schema::dropIfExists('material_requests');
    }
}
