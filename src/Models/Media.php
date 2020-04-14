<?php

namespace Laracms\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'extension', 'size', 'mime_type', 'type', 'url', 'alt', 'description', 'location', 'folder', 'author', 'created_at'
    ];

    /**
     * Scope a query to only include popular.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($query)
    {
        return $query->select('id', 'name', 'extension', 'size', 'mime_type', 'type', 'url', 'alt', 'description', 'location', 'folder', 'author', 'created_at');
    }
}
