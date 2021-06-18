<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubCategoryProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sub_category_product';
    protected $fillable = ['category_id', 'name', 'description'];
    protected $primaryKey = 'id';

    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_id', 'id');
    }

    public function model()
    {
        return $this->morphOne(MediaImage::class, 'model');
    }

    public function catalogue()
    {
        return $this->hasMany(CatalogueProduct::class, 'sub_cat_id', 'id');
    }
}
