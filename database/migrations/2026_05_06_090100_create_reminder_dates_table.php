<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminder_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reminder_id')->constrained('reminders')->cascadeOnDelete();
            $table->date('reminder_date');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['reminder_date', 'is_sent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminder_dates');
    }
};
