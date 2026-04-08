<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_orders', function (Blueprint $table) {
            $table->string('link')->nullable()->after('status');
            $table->text('rejection_reason')->nullable()->after('link');
            $table->foreignId('validated_by')->nullable()->after('rejection_reason')->constrained('users');
            $table->timestamp('validated_at')->nullable()->after('validated_by');
        });
    }

    public function down(): void
    {
        Schema::table('booking_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('validated_by');
            $table->dropColumn(['link', 'rejection_reason', 'validated_at']);
        });
    }
};
