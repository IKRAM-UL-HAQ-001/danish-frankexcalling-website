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
        Schema::create('data_entries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('feedback')->nullable();
            $table->string('amount')->nullable();
            $table->string('task_name')->nullable();
            $table->unsignedBigInteger('phone_id')->nullable();
            $table->foreign('phone_id')->references('id')->on('phone_numbers')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('exchange_id')->nullable();
            $table->foreign('exchange_id')->references('id')->on('exchanges')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_entries');
    }
};
