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
        Schema::create('domain_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCompany');
            $table->string('domainName');
            $table->string('titleName');
            $table->string('imap');
            $table->string('pop3');
            $table->string('smtp');
            $table->text('description');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_emails');
    }
};
