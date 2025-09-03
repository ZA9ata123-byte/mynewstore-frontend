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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // --- التصحيح 1: جعل user_id اختيارياً (nullable) لقبول طلبات الزوار ---
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            
            // --- تم تغيير الاسم من total_amount إلى total_price ليتوافق مع الكود ---
            $table->decimal('total_price', 10, 2);
            
            $table->string('status')->default('pending');
            
            // --- التصحيح 2: دمج كل معلومات الشحن في حقل JSON واحد ---
            $table->json('shipping_info');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};