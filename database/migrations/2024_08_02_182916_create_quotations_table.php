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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_no');
            $table->date('date');
            $table->time('time')->nullable();
            $table->text('subject');
            $table->string('client_name');
            $table->string('client_mobile');
            $table->string('status')->default('pending');
            $table->text('client_address');
            $table->integer('createdBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
