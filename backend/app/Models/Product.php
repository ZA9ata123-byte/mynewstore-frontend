<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'short_description', 
        'price', 'product_type', 'stock',
    ];

    protected $appends = ['total_stock', 'price_range'];

    /**
     * --- هنا الإصلاح الأول ---
     * تم حذف الأقواس من variants() لتصبح variants
     * هذا يجعلها تستخدم المتغيرات التي تم تحميلها مسبقاً
     */
    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->product_type === 'simple') {
                    return $this->stock;
                }
                // استخدمنا العلاقة المحملة مسبقًا لتجنب استعلام إضافي
                return $this->relationLoaded('variants') ? $this->variants->sum('stock') : 0;
            }
        );
    }

    /**
     * --- هنا الإصلاح الثاني ---
     * نفس الشيء، حذفنا الأقواس () من variants
     */
    protected function priceRange(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->product_type === 'simple') {
                    return "{$this->price} د.م.";
                }

                // تأكد من أن المتغيرات قد تم تحميلها لتجنب الأخطاء
                if (!$this->relationLoaded('variants') || $this->variants->isEmpty()) {
                    return 'N/A'; // أو أي قيمة افتراضية
                }
                
                // استخدمنا العلاقة المحملة مسبقًا
                $minPrice = $this->variants->min('price');
                $maxPrice = $this->variants->max('price');

                if ($minPrice && $maxPrice) {
                    if ($minPrice == $maxPrice) {
                        return "{$minPrice} د.م.";
                    }
                    return "{$minPrice} - {$maxPrice} د.م.";
                }
                
                return "{$this->price} د.م.";
            }
        );
    }

    // --- العلاقات (تبقى كما هي) ---
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function options() { return $this->hasMany(ProductOption::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }

    public function isVariable(): bool
    {
        return $this->product_type === 'variable';
    }
}

    