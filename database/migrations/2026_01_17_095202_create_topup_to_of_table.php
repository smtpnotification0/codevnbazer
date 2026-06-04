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
        Schema::create('topup_to_of', function (Blueprint $table) {
            $table->id();
            
            // status: 1 থাকলে on, 0 থাকলে off (ডিফল্ট হিসেবে 0 রাখা হয়েছে)
            $table->boolean('status')->default(0); 
            
            // balance_detect: এমাউন্ট লেখার জন্য (দশমিক সংখ্যা সাপোর্ট করবে)
            $table->decimal('balance_detect', 10, 2)->default(0.00);
            
            // Player ID বক্সগুলো (খালি থাকতে পারবে বা nullable)
            $table->string('player_id_1')->nullable();
            $table->string('player_id_2')->nullable();
            $table->string('player_id_3')->nullable();
            $table->string('player_id_4')->nullable();
            $table->string('player_id_5')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topup_to_of');
    }
};