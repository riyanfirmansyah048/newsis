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
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idDomainEmail')->nullable();
            // $table->unsignedBigInteger('idUser')->unique();
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('idCompany');
            $table->string('emailName');
            $table->string('passwordEmail')->nullable();
            $table->boolean('activeStatus')->default(false);
            $table->date('activeDate')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emails');
    }
};
