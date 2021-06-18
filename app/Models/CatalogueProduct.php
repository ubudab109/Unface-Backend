<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogueProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'catalogue_product';
    protected $fillable = ['sub_cat_id', 'name', 'description'];
    protected $primaryKey = 'id';

    public function subCategory()
    {
        return $this->belongsTo(SubCategoryProduct::class, 'sub_cat_id', 'id');
    }

    public function model()
    {
        return $this->morphOne(MediaImage::class, 'model');
    }
}
