<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'size';
    protected $fillable = ['name', 'size'];
    protected $primaryKey = 'id';

    public function ProductCollection()
    {
        return $this->hasMany(ProductSizeCollection::class, 'size_id', 'id');
    }
}
