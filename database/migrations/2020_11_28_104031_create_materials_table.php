<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string('code')->unique()->nullable(false);
            $table->string('serial_no')->unique();
            $table->string('name')->nullable(false);
            $table->string('color')->nullable(true);
            $table->decimal('cost_price')->default(0);
            $table->string('manufacture')->nullable(true);

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
        Schema::dropIfExists('materials');
    }
}
