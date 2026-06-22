<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bppb_software', function (Blueprint $table) {
            $table->unsignedBigInteger('source_bppb_software_id')
                ->nullable()
                ->after('noBppbPemohon')
                ->comment('ID bppb_software asal milik user saat ditarik admin');
        });
    }

    public function down(): void
    {
        Schema::table('bppb_software', function (Blueprint $table) {
            $table->dropColumn('source_bppb_software_id');
        });
    }
};
