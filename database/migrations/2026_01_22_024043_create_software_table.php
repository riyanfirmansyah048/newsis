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
        Schema::create('software', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('product_form_id'); // Bentuk software
            $table->unsignedBigInteger('type_id'); // Jenis software
            $table->unsignedBigInteger('category_software_id'); // Kategori software
            $table->unsignedBigInteger('brand_software_id'); // Merek software
            $table->unsignedBigInteger('unit_id'); // Satuan software
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
        Schema::dropIfExists('software');
    }
};
