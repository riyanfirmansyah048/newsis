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
        Schema::create('bppb_inks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bppb_id')->nullable();
            $table->unsignedBigInteger('ink_id');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->integer('qty');
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
        Schema::dropIfExists('bppb_inks');
    }
};
