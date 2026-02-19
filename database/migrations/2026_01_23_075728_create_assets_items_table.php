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
        Schema::create('assets_items', function (Blueprint $table) {
            $table->id();
            $table->integer('number'); //nomor urut
            $table->string('noAssetItem');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('bpb_id');
            $table->unsignedBigInteger('bppb_item_id')->nullable(); //bppb_item atau detail dari item bppb
            $table->integer('numberOwner'); //Owner ke berapa
            $table->integer('idCompany')->length(11)->nullable();
            $table->integer('idRegional')->length(11)->nullable();
            $table->integer('idBusinessUnit')->length(11)->nullable();
            $table->integer('idDepartment')->length(11)->nullable();
            $table->integer('idPosition')->length(11)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets_items');
    }
};
