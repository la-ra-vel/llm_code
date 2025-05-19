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
        Schema::create('client_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id')->comment('Foreign key to clients table');
            $table->string('caseID');
            $table->integer('invoice_no');
            $table->integer('court_catID')->nullable();
            $table->string('court_case_no')->nullable();
            $table->text('case_court_address')->nullable();
            $table->string('case_location')->nullable();
            $table->string('responded_adv')->nullable();
            $table->string('responded_adv_mobile')->nullable();
            $table->string('fir_no')->nullable();
            $table->json('case_acts')->nullable();
            $table->longText('case_legal_matter');
            $table->string('opponent_name');
            $table->string('opponent_mobile')->nullable();
            $table->text('opponent_address')->nullable();
            $table->date('case_start_date')->nullable();
            $table->date('case_end_date')->nullable();
            $table->string('status')->default('open');
            $table->integer('counter_invoice_download')->default(0);
            $table->string('createdBy')->nullable()->comment('users');
            // $table->softDeletes();
            $table->timestamps();

            // Define foreign key constraint
            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade'); // Example: Cascade delete if court_detail is deleted
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_cases');
    }
};
