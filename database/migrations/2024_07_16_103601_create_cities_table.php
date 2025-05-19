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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('state_id')->comment('Foreign key to states table');
            $table->string('name');
            $table->string('status')->default('active');
            $table->integer('createdBy')->nullable()->comment('users');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('state_id')
                ->references('id')->on('states')
                ->onDelete('cascade'); // Example: Cascade delete if states is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
