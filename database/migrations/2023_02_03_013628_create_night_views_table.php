<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('night_views', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('場所名');
            $table->string('address')->comment('所在地');
            $table->geometry('location')->comment('緯度・経度');
            $table->timestamps();

            $table->spatialIndex('location');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('night_views');
    }
};
