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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('city_id')->comment('Foreign key to cities table');
            $table->unsignedBigInteger('court_categoryID')->comment('Foreign key to court_categories table');
            $table->string('location')->nullable();
            $table->string('court_name');
            $table->string('court_room_no')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->integer('createdBy')->nullable();
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('cascade'); // Example: Cascade delete if city is deleted

                $table->foreign('court_categoryID')
                ->references('id')->on('court_categories')
                ->onDelete('cascade'); // Example: Cascade delete if court_categories is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
