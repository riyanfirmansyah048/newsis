<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bpbs', function (Blueprint $table) {
            $table->id();
            $table->integer('number'); //nomor urut
            $table->string('noBpb');
            $table->unsignedBigInteger('po_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // id user yang menerima bpb/membuat bpb
            $table->dateTime('dateBpb')->nullable(); // tanggal diterima
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpbs');
    }
};
