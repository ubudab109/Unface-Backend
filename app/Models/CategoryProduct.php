<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'category_product';
    protected $fillable = ['name', 'description'];
    protected $primaryKey = 'id';

    public function subCategory()
    {
        return $this->hasMany(SubCategoryProduct::class, 'category_id', 'id');
    }

    public function model()
    {
        return $this->morphOne(MediaImage::class, 'model');
    }
}
