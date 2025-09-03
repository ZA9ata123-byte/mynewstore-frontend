<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'price', 'sku', 'stock'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * العلاقة مع قيم الخيارات: كل متغير يتكون من عدة قيم
     */
    public function optionValues()
    {
        return $this->belongsToMany(ProductOptionValue::class, 'product_variant_option_value');
    }
}