<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('cart_token')->unique()->nullable()->after('user_id');
        });
    }
    public function down(): void {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('cart_token');
        });
    }
};