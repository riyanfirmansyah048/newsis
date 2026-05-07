<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reminders') || Schema::hasColumn('reminders', 'cc')) {
            return;
        }

        Schema::table('reminders', function (Blueprint $table) {
            $table->text('cc')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('reminders') || ! Schema::hasColumn('reminders', 'cc')) {
            return;
        }

        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn('cc');
        });
    }
};
