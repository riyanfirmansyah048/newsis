<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_type_id')->constrained('booking_types');
            $table->string('name');
            $table->string('identifier')->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_units');
    }
};
