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
        Schema::create('inks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product_form_id'); // Bentuk Tinta
            $table->unsignedBigInteger('type_id'); // Jenis Tinta
            $table->unsignedBigInteger('category_ink_id'); // Kategori Tinta
            $table->unsignedBigInteger('brand_ink_id'); // Merek Tinta
            $table->unsignedBigInteger('unit_id'); // Satuan Tinta
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
        Schema::dropIfExists('inks');
    }
};
