<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock_quantity',
        'attributes',
        'image_url',
    ];

    protected $casts = [
        'attributes' => 'array', // This tells Laravel to treat the 'attributes' column as an array
    ];

    /**
     * Get the product that owns the ProductVariant
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}