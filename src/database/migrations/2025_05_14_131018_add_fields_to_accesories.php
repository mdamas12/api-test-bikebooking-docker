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
        Schema::table('accesories', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->integer('quantity')->default(0); 
            $table->integer('order')->default(1); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accesories', function (Blueprint $table) {
            $table->dropColumn('quantity');
            $table->dropColumn('order');
            $table->text('description')->nullable();
        });
    }
};
