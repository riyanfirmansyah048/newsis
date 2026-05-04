<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->text('pending')->nullable()->after('status_id');
        });

        if (! DB::table('service_statuses')->where('id', 8)->exists()) {
            DB::table('service_statuses')->insert([
                'id' => 8,
                'name' => 'Pending',
                'description' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('pending');
        });
    }
};
