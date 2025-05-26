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
        Schema::create('accesories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_accesory_id')->constrained()->onDelete('cascade');
            $table->string('name',60);
            $table->string('path',255);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->decimal('price_booking', 10, 2)->default(0);
            $table->decimal('price_day', 10, 2)->default(0); 
            $table->boolean('is_price_booking')->default(false);
            $table->timestamps(); 
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accesories');
    }
};
