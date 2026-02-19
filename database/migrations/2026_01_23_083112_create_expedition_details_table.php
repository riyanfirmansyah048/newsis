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
        Schema::create('expedition_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expedition_id')->nullable();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->unsignedBigInteger('product_form_id')->nullable(); // jenis produk items, inks, komputer, software
            $table->unsignedBigInteger('type_id')->nullable(); //id items, inks, komputer, softwares
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expedition_details');
    }
};
