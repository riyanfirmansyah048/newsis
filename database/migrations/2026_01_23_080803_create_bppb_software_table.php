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
        Schema::create('bppb_software', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bppb_id')->nullable();
            $table->unsignedBigInteger('software_id');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->integer('qty');
            $table->text('description')->nullable();
            $table->string('noBppbPemohon')->nullable();
            $table->unsignedBigInteger('pemohonIT')->nullable(); // id user
            $table->string('userPemohon')->nullable();
            $table->string('departementPemohon')->nullable();
            $table->string('lokasiPemohon')->nullable();
            $table->string('serialNumber')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bppb_software');
    }
};
