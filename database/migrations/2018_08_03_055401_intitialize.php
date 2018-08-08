<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Intitialize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_key', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('division');
            $table->boolean('isallowed');
            $table->timestamps();
            $table->SoftDeletes();
        });
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->text('token');
            $table->text('event_id');
            $table->text('event_name');
            $table->dateTime('booktime_start')->nullable();
            $table->dateTime('booktime_end')->nullable();
            $table->string('airport_departure');
            $table->string('airport_arrival');
            $table->timestamps();
            $table->SoftDeletes();
        });
        Schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->text('event_id');
            $table->text('flight_id');
            $table->string('user_division');
            $table->string('user_vid');
            $table->string('user_email');
            $table->string('user_rating');
            $table->string('aircraft_callsign');
            $table->string('aircraft_model');
            $table->string('flight_rule');
            $table->string('flight_type');
            $table->string('flight_load');
            $table->time('time_departure');
            $table->time('time_arrival');
            $table->timestamps();
            $table->SoftDeletes();
        });
        Schema::create('confirmpool', function (Blueprint $table) {
            $table->increments('id');
            $table->text('ticket_code');
            $table->dateTime('ticket_timeout')->nullable();
            $table->text('event_id');
            $table->string('user_division');
            $table->string('user_vid');
            $table->string('user_email');
            $table->string('user_rating');
            $table->string('aircraft_callsign');
            $table->string('aircraft_model');
            $table->string('flight_rule');
            $table->string('flight_type');
            $table->string('flight_load');
            $table->time('time_departure');
            $table->time('time_arrival');
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
        Schema::drop('access_key');
        Schema::drop('events');
        Schema::drop('flights');
        Schema::drop('confirmpool');
    }
}
