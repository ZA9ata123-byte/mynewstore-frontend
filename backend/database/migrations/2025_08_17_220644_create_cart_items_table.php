<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint; // <== تم تصحيح الخطأ هنا
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();

            // الربط مع السلة
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');

            // الربط مع المنتج
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // الكمية
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};