<?php

use App\Constants\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('product_id')->nullable();
            $table->foreignId('variation_id')->nullable();
            $table->decimal('amount', 16, 2)->default(0.00);
            $table->text('delivery_message')->nullable();
            $table->json('account_info')->nullable();
            $table->json('provider_data')->nullable();
            $table->string('voucher_code', 255)->nullable();
            $table->string('track_id', 25)->nullable();
            $table->integer('quantity')->default(1);
            $table->tinyInteger('attempts')->default(0);
            $table->enum('status', OrderStatus::ORDERLIST)->default('processing');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropIfExists();
        });
    }
};
