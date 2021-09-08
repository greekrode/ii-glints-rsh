<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = ['title','path','todo_id','created_at','updated_at'];

    public function todo()
    {
        return $this->belongsTo('todo');
    }
}
