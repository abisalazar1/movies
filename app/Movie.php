<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    /**
     * allows mass assigment
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description'
    ];

    /**
     * Has many movie times
     *
     * @return hasMany
     */
    public function times()
    {
        return $this->hasMany(MovieTime::class);
    }
}
