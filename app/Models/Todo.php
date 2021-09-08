<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['title','body','completed','created_at','updated_at'];
    protected $casts = ['completed' => 'boolean'];

    public function image()
    {
        return $this->hasOne(Image::class);
    }
}
