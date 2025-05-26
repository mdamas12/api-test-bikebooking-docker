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
        Schema::table('stock_items', function (Blueprint $table) {
            $table->dropColumn('size');
            $table->dropColumn('price');
            $table->foreignId('size_id')->constrained()->onDelete('cascade');
            $table->date('arrival')->nullable();
            $table->date('output')->nullable(); 
            $table->string('dimension',60);
            $table->string('serial',100)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_items', function (Blueprint $table) {
            if (Schema::hasColumn('stock_items', 'size_id')) {
          
                $table->dropForeign(['size_id']); 
                $table->dropColumn('size_id');
            }
            if (Schema::hasColumn('stock_items', 'arrival')) {
                $table->dropColumn('arrival');
            }
    
            if (Schema::hasColumn('stock_items', 'output')) {
                $table->dropColumn('output');
            }
    
            if (Schema::hasColumn('stock_items', 'dimension')) {
                $table->dropColumn('dimension');
            }
      
              $table->enum('size',['XS','S','M','L','XL']);
              $table->decimal('price', 10, 2)->default(0);
      
            
        });
    }
};
