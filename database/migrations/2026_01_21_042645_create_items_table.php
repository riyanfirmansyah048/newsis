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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product_form_id'); // Bentuk barang
            $table->unsignedBigInteger('type_id'); // Jenis barang
            $table->unsignedBigInteger('category_id'); // Kategori barang
            $table->unsignedBigInteger('brand_id'); // Merek barang
            $table->unsignedBigInteger('unit_id'); // Satuan barang
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
        Schema::dropIfExists('items');
    }
};
