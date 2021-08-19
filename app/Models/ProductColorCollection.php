<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductColorCollection extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_color_collection';
    protected $fillable = ['product_size_id', 'color_id', 'stock'];
    protected $primaryKey = 'id';
    protected $hidden = ['product_size_id', 'created_at', 'updated_at', 'deleted_at'];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }

    public function productSize()
    {
        return $this->belongsTo(ProductSizeCollection::class, 'product_size_id', 'id');
    }
}
