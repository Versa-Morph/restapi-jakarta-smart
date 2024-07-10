<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nik')->nullable();
            $table->string('profile_pict_path')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->integer('age')->nullable();
            $table->string('blood_type')->nullable();
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('phone_number')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bio');
    }
}
