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
        Schema::table('product_variants', function (Blueprint $table) {
            // كنزيدو عمود جديد باش نربطو كل متغير بصورة معينة من جدول الصور
            $table->foreignId('product_image_id')
                  ->nullable()
                  ->constrained('product_images')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // هادشي كيمكننا نرجعو اللور إلا بغينا نحيدو هاد التعديل
            $table->dropForeign(['product_image_id']);
            $table->dropColumn('product_image_id');
        });
    }
};