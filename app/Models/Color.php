<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'color';
    protected $fillable = ['name', 'hex_color'];
    protected $primaryKey = 'id';

    public function ProductCollection()
    {
        return $this->hasMany(ProductColorCollection::class, 'color_id', 'id');
    }
}
