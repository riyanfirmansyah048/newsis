<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_types', function (Blueprint $table) {
            $table->text('notification_cc')->nullable()->after('notification_email');
        });
    }

    public function down(): void
    {
        Schema::table('booking_types', function (Blueprint $table) {
            $table->dropColumn('notification_cc');
        });
    }
};
