<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableZoneLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zone_location', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->nullable();
            $table->string('ecozone')->nullable();
            $table->string('type')->nullable();
            $table->string('nature')->nullable();
            $table->string('address')->nullable();
            $table->string('developer')->nullable();
            $table->string('city')->nullable();
            $table->string('city_name')->nullable();
            $table->string('province')->nullable();
            $table->string('province_name')->nullable();
            $table->string('region_code')->nullable();
            $table->string('region')->nullable();
            $table->string('region_name')->nullable();
            $table->string('dev_comp_code')->nullable();
            $table->string('obo_cluster')->nullable();
            $table->string('income_cluster')->nullable();
            $table->string('serial')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_zone_location');
    }
}
