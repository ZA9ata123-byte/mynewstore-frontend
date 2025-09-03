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

    // --- هنا تمت إضافة الذكاء ---
    protected $appends = ['total_stock', 'price_range'];

    protected function totalStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->product_type === 'simple') {
                    return $this->stock;
                }
                return $this->variants()->sum('stock');
            }
        );
    }

    /**
     * --- هادي هي الدالة السحرية الجديدة لي كتحسب لينا مجال السعر ---
     */
    protected function priceRange(): Attribute
    {
        return Attribute::make(
            get: function () {
                // إذا كان المنتج بسيط، رجع السعر العادي
                if ($this->product_type === 'simple') {
                    return "{$this->price} د.م.";
                }

                // إذا كان المنتج متغير، احسب أصغر وأكبر سعر
                $minPrice = $this->variants()->min('price');
                $maxPrice = $this->variants()->max('price');

                if ($minPrice && $maxPrice) {
                    if ($minPrice == $maxPrice) {
                        return "{$minPrice} د.م.";
                    }
                    return "{$minPrice} - {$maxPrice} د.م.";
                }

                // في حالة عدم وجود متغيرات، رجع السعر الأساسي
                return "{$this->price} د.م.";
            }
        );
    }

    // --- العلاقات ---
    public function category() { return $this->belongsTo(Category::class); }
    public function images() { return $this->hasMany(ProductImage::class); }
    public function options() { return $this->hasMany(ProductOption::class); }
    public function variants() { return $this->hasMany(ProductVariant::class); }

    /**
     * --- هادي هي الدالة الجديدة اللي زدنا ---
     * Check if the product is of variable type.
     *
     * @return bool
     */
    public function isVariable(): bool
    {
        return $this->product_type === 'variable';
    }
}