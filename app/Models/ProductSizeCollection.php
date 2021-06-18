<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSizeCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_size_collection';
    protected $fillable = ['product_id', 'size_id', 'stock', 'price'];
    protected $primaryKey = 'id';
    protected $hidden = ['product_id', 'size_id', 'created_at', 'updated_at', 'deleted_at'];
    protected $appends = ['discount_price'];

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }

    public function productColor()
    {
        return $this->hasMany(ProductColorCollection::class, 'product_size_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function getDiscountPriceAttribute()
    {
        $discount = $this->product()->find($this->product_id);
        if ($discount->discount != null) {
            if ($discount->discount_type == 'fixed') {
                return $this->price - $discount->discount;
            } else {
                $calc = $this->price * ($discount->discount / 100);
                return $this->price - $calc;
            }
        } else {
            return null;
        }
    }
}
