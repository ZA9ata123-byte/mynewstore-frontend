<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_option_value', function (Blueprint $table) {
            $table->id();

            // نربط مع جدول المتغيرات
            $table->foreignId('product_variant_id')
                  ->constrained('product_variants')
                  ->onDelete('cascade');

            // نربط مع جدول قيم الخيارات
            $table->foreignId('product_option_value_id')
                  ->constrained('product_option_values')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_option_value');
    }
};