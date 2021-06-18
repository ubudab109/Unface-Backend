<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product';
    protected $fillable = ['catalogue_id', 'material_id', 'name', 'description', 'discount', 'discount_type'];
    protected $primaryKey = 'id';
    protected $appends = ['min_price', 'max_price', 'total_discount', 'sub_category', 'category'];
    protected $hidden = ['catalogue_id', 'material_id'];

    public function catalogue()
    {
        return $this->belongsTo(CatalogueProduct::class, 'catalogue_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }

    public function model()
    {
        return $this->morphMany(MediaImage::class, 'model');
    }

    public function SizeCollection()
    {
        return $this->hasMany(ProductSizeCollection::class, 'product_id', 'id');
    }

    public function getTotalDiscountAttribute()
    {
        if ($this->discount != null) {
            $this->attributes['total_discount'] = $this->discount;
            return  $this->attributes['total_discount'];
        }
        return null;
    }

    public function getMinPriceAttribute()
    {
        return $this->SizeCollection()->min('price');
    }

    public function getMaxPriceAttribute()
    {
        return $this->SizeCollection()->max('price');
    }

    public function getSubCategoryAttribute()
    {
        return $this->catalogue()->first()->subCategory()->select('id', 'name')->first();
    }

    public function getCategoryAttribute()
    {
        return $this->catalogue()->first()->subCategory()->first()->category()->select('id', 'name')->first();
    }
}
