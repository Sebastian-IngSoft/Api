<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];
    protected $allowIncluded = ['posts', 'posts.user'];
    protected $allowFilter = ['id','name', 'slug'];
    // Relación uno a muchos
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Scope a query to include specified relationships if they are allowed.
     *
     * This scope method checks if the 'included' parameter is present in the request.
     * If it is, it filters the requested relationships against the allowed relationships
     * defined in the model. Only the allowed relationships are included in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeIncluded(Builder $query)
    {
        //si no hay parametro included
        if (empty($this->allowIncluded) || empty(request('included'))) {
            return;
        }   


        //variable de la url, el explode es un helper de laravel no de php
        $relations = explode(',',request('included'));
        $allowIncluded = collect($this->allowIncluded);
        foreach ($relations as $key => $relation) {
            if (!$allowIncluded->contains($relation)) {
                unset($relations[$key]);
            }
        }
        // dd($allowIncluded);
        // modifica la query para que traiga las relaciones
        $query->with($relations);
    }

    public function scopeFilter(Builder $query)
    {
        if (empty($this->allowFilter) || empty(request('filter'))) {
            return;
        }

        $filters = request('filter');
        $allowFilter = collect($this->allowFilter);
        foreach ($filters as $key => $value) {
            if ($allowFilter->contains($key)) {
                $query->where($key, 'LIKE', "%$value%");
            }
        }
    }
}
