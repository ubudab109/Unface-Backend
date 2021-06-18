<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'material';
    protected $fillable = ['name'];
    protected $primaryKey = 'id';

    public function product()
    {
        return $this->hasMany(Product::class, 'material_id', 'id');
    }
}
