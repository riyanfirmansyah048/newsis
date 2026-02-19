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
        Schema::create('expeditions', function (Blueprint $table) {
            $table->id();
            $table->integer('number'); //nomor urut
            $table->string('noExpedition')->nullable();
            $table->string('expeditor')->nullable();
            $table->unsignedBigInteger('bppb_id')->nullable();
            $table->dateTime('dateInput')->nullable();
            $table->date('dateStart')->nullable();
            $table->date('dateFinish')->nullable();
            $table->date('datePrint')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expeditions');
    }
};
