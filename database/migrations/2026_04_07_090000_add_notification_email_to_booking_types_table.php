<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_types', function (Blueprint $table) {
            $table->string('notification_email')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('booking_types', function (Blueprint $table) {
            $table->dropColumn('notification_email');
        });
    }
};
