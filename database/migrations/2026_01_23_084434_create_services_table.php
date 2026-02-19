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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // id user yang mengajukan Service
            $table->unsignedBigInteger('ic_id')->nullable(); // id user yang mengerjakan Service
            $table->integer('number'); //nomor urut
            $table->string('noService'); // serial number service
            $table->string('serialNumberItem')->nullable(); // serial number item
            $table->unsignedBigInteger('item_id'); // id item yang service
            $table->unsignedBigInteger('vendor_id')->nullable(); // id vendor
            $table->unsignedBigInteger('type_service_id')->nullable();
            $table->unsignedBigInteger('solution_id')->nullable();
            $table->text('problem')->nullable();
            $table->integer('estimation')->nullable(); //estimasi hari pengerjan
            $table->text('analisa')->nullable();
            $table->text('analisa_reject')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->dateTime('work_date')->nullable();
            $table->dateTime('finish_date')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
