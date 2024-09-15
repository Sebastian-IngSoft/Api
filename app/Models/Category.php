<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];
    protected $allowIncluded = ['posts', 'posts.user'];
    protected $allowFilter = ['id','name', 'slug'];
    protected $allowSort = ['id','name', 'slug'];
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

    /**
     * Scope a query to apply filters based on the request parameters.
     *
     * This scope method checks if there are any allowed filters defined in the
     * model and if there are any filter parameters in the request. If both are
     * present, it applies the filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
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
    /**
     * Scope a query to sort results based on request parameters.
     *
     * This scope method allows sorting of query results based on the 'sort' parameter
     * in the request. The 'sort' parameter can contain multiple fields separated by commas.
     * Each field can be prefixed with a '-' to indicate descending order.
     *
     * Common uses of the Laravel Str library:
     * - Str::startsWith($haystack, $needles): Determine if a given string starts with a given substring.
     * - Str::substr($string, $start, $length = null): Return the portion of the string specified by the start and length parameters.
     * - Str::contains($haystack, $needles): Determine if a given string contains a given substring.
     * - Str::finish($value, $cap): Ensure a string ends with a single instance of a given value.
     * - Str::slug($title, $separator = '-'): Generate a URL-friendly "slug" from a given string.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return void
     */
    public function scopeSort($query){
        if (empty($this->allowSort) || empty(request('sort'))) {
            return;
        }
        $sortFields = explode(',', request('sort'));
        $allowSort = collect($this->allowSort);
        foreach ($sortFields as $sortField) {
            $direction = 'asc';
            if (Str::startsWith($sortField, '-')) {
                $direction = 'desc';
                $sortField = Str::substr($sortField, 1);
            }
            if ($allowSort->contains($sortField)) {
                $query->orderBy($sortField, $direction);
            }
        }
    }
}
