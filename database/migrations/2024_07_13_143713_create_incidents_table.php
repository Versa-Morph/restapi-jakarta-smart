<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateIncidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_number')->unique();
            $table->string('caller');
            $table->string('responder');
            $table->string('image')->nullable();
            $table->timestamp('request_datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('process_datetime')->nullable();
            $table->timestamp('complete_datetime')->nullable();
            $table->enum('status', ['requested', 'processed', 'completed'])->default('requested');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('incidents');
    }
}
