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
        Schema::create('user_infos', function (Blueprint $table) {
            $table->id();
            $table->string('dob', 64);
            $table->string('address', 64);
            $table->string('city', 64);
            $table->string('state_id', 64);
            $table->string('zip', 64);
            $table->string('country_id', 64);
            $table->string('account_type', 64);
            $table->string('closest_airport', 64);
            $table->string('code', 64);
            $table->string('population', 64);
            $table->index('state_id', 'population');
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
        Schema::dropIfExists('user_infos');
    }
};
