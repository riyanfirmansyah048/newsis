<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('reminders') || Schema::hasColumn('reminders', 'software_id')) {
            return;
        }

        Schema::table('reminders', function (Blueprint $table) {
            $table->foreignId('software_id')->nullable()->after('item_id')->constrained('software')->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('reminders') || ! Schema::hasColumn('reminders', 'software_id')) {
            return;
        }

        Schema::table('reminders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('software_id');
        });
    }
};
