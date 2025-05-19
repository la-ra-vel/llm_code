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
        Schema::create('quotation_descriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('quotation_id');
            $table->longText('description');
            $table->float('amount');
            $table->dateTime('date');
            $table->integer('createdBy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_descriptions');
    }
};
