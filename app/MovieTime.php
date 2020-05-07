<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovieTime extends Model
{
    /**
     * Allows massassigment
     *
     * @var array
     */
    protected $fillable = [
        'movie_id',
        'date_time'
    ];

    /**
     * BelongsTo
     *
     * @return belongsTo
     */
    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    /**
     * Customer Bookings
     *
     * @return hasMany
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Numbers of taken seats
     *
     * @return hasMany
     */
    public function seatsTaken()
    {
        return optional($this->bookings()->selectRaw('SUM(seats) AS seats')->first())->seats;
    }

    /**
     * Gets the numbers of seats available
     *
     * @return int
     */
    public function seatsAvailable()
    {
        return 10 - $this->seatsTaken();
    }

    /**
     * checks if the time has seats available
     *
     * @return bool
     */
    public function hasSeatsAvailable()
    {
        return $this->seatsTaken() < 10;
    }
}
