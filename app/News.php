<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'category_id', 'author'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

}
