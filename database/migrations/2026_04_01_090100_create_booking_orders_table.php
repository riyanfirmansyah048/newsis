<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('booking_type_id')->constrained('booking_types');
            $table->string('topic');
            $table->string('host');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status')->default('pending');
            $table->foreignId('assigned_unit_id')->nullable()->constrained('booking_units');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['booking_type_id', 'date']);
            $table->index(['status', 'booking_type_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_orders');
    }
};
