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
        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sitename');
            $table->string('title');
            $table->text('address')->nullable();
            $table->string('mobile')->nullable();
            $table->string('law_firm_admin')->nullable();
            $table->string('law_firm_lawyer')->nullable();
            $table->text('copy_r')->nullable();
            $table->string('email');
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->string('default_image')->nullable();
            $table->string('login_image')->nullable();
            $table->string('email_from')->nullable();
            $table->string('email_method')->nullable();
            $table->text('smtp_config')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
