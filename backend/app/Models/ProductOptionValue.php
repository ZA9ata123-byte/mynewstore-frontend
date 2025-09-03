<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    use HasFactory;

    protected $fillable = ['product_option_id', 'value'];

    /**
     * العلاقة العكسية: كل قيمة تنتمي لخيار واحد
     */
    public function option()
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    /**
     * العلاقة مع المتغيرات: كل قيمة يمكن أن تكون في عدة متغيرات
     */
    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_option_value');
    }
}