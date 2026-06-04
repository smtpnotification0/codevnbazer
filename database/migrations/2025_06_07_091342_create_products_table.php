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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('slug')->unique();
            $table->foreignId('categorie_id')->constrained('categories')->onUpdate('cascade')->onDelete('cascade');
            $table->text('content');
            $table->enum('type', ['INGAME', 'IDCODE', 'VOUCHER', 'SUBSCRIPTION']);
            $table->integer('percentage')->nullable()->default(0);
            $table->integer('uid_checker')->nullable()->default(0);
            $table->text('image');
            $table->integer('slot')->default(0);
            $table->string('input')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['categorie_id']);
            $table->dropIfExists();
        });
    }
};
