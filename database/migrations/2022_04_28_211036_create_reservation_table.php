<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctor');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('service_id')->constrained('service');
            $table->date('date');
            $table->time('time');
            $table->text('message')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('name');
            $table->string('phone');
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
        Schema::dropIfExists('products');
    }
}
