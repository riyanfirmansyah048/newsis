<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bppbs', function (Blueprint $table) {
            $table->id();
            $table->string('noBppb')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // id user yang mengajukan bppb
            $table->unsignedBigInteger('user_validation_id')->nullable(); // id user yang memvalidasi bppb (IT)
            $table->unsignedBigInteger('bppb_type_id')->nullable(); // id type bppb
            $table->unsignedBigInteger('service_id')->nullable(); // id Service
            $table->unsignedBigInteger('user_service_id')->nullable(); // id user yang service Service (IT)
            $table->integer('number')->nullable();
            $table->unsignedBigInteger('status_id')->nullable(); // id status
            $table->text('description')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bppbs');
    }
};
