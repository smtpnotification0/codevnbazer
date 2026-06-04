<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('account_info_to')->nullable()->after('id');
            $table->string('account_info_original')->nullable()->after('account_info_to');
            $table->string('order_id_to')->nullable()->after('account_info_original');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['account_info_to', 'account_info_original', 'order_id_to']);
        });
    }
};