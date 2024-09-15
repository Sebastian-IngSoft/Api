<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory, ApiTrait;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $fillable = [
        'name',
        'slug',
        'extract',
        'body',
        'status',
        'category_id',
        'user_id'
    ];

    //relación uno a muchos inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //relación uno a muchos
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    //relación uno a muchos polimórfica
    public function images()
    {
        //el segundo parametro es como se llama funcion en image
        return $this->morphMany(Image::class, 'imageable');
    }
}
