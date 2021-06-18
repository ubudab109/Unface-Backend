<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaImage extends Model
{
    use HasFactory;

    protected $table = 'media_image';
    protected $primaryKey = 'id';
    protected $fillable = ['src', 'model_type', 'model_id'];
    protected $hidden = ['model_id', 'model_type'];

    public function model()
    {
        return $this->morphTo();
    }
}
