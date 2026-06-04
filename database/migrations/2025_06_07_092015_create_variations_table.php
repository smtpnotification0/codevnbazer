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
        Schema::create('variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onUpdate('cascade')->onDelete('cascade');
            $table->string('title');
            $table->decimal('price', 16, 2)->default(0.00);
            $table->decimal('gift_coins', 16, 2)->default(0.00);
            $table->integer('stock')->default(0);
            $table->tinyInteger('automatic')->default(0);
            $table->string('provider')->nullable();
            $table->string('provider_product_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variations', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIfExists();
        });
    }
};
