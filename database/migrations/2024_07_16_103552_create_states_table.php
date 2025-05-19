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
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->comment('Foreign key to countries table');
            $table->string('name');
            $table->string('status')->default('active');
            $table->integer('createdBy')->nullable()->comment('users');
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->onDelete('cascade'); // Example: Cascade delete if countries is deleted

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
