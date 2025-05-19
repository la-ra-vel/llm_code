<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fee_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_case_pid')->comment('Foreign key to client_cases table');
            $table->integer('fee_description_id');
            $table->float('amount');
            $table->text('remarks');
            $table->string('createdBy')->nullable()->comment('users');
            // $table->softDeletes();
            $table->timestamps();

             // Define foreign key constraint
             $table->foreign('client_case_pid')
             ->references('id')->on('client_cases')
             ->onDelete('cascade'); // Example: Cascade delete if court_detail is deleted

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_details');
    }
};
