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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('contact_name')->max(20)->nullable();
            $table->string('email')->unique();
            $table->string('company_name')->max(20)->nullable();
            $table->string('phone')->max(20)->nullable();
            $table->string('fiscal_name')->max(20)->nullable();
            $table->string('cif')->max(20)->unique();
            $table->text('address')->max(60)->nullable();
            $table->string('country')->max(20)->nullable();
            $table->string('website_url')->max(20)->nullable();
            $table->enum('status',['pending','in progress','active','disabled','testing']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
