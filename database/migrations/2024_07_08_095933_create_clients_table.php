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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('fname');
            $table->string('lname');
            $table->string('mobile');
            $table->string('wp_no');
            $table->string('email')->unique()->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('pincode');
            // $table->date('visiting_date');
            $table->string('gender');
            $table->string('occupation')->nullable();
            $table->string('status')->default('active');
            $table->integer('createdBy')->nullable()->comment('users');
            // $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
