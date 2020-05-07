<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * Allows mass assigment
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'name',
    ];

    /**
     * HasMany
     *
     * @return hasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
