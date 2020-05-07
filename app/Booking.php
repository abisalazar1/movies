<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /**
     * allows mass assigment
     *
     * @var array
     */
    protected $fillable = [
        'seats',
        'customer_id',
        'movie_time_id'
    ];

    /**
     * BelongsTo
     *
     * @return belongsTo
     */
    public function moveTime()
    {
        return $this->belongsTo(MovieTime::class);
    }

    /**
     * Belongsto
     *
     * @return belongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Increments the number of seats taken
     *
     * @param int $seats
     *
     * @return self
     */
    public function addSeat(int $seats = 1)
    {
        $this->increment('seats', $seats);

        return $this;
    }

    /**
     * Removes the numbers of seats
     *
     * @param int $seats
     *
     * @return self
     */
    public function removeSeat(int $seats = 1)
    {
        $this->decrement('seats', $seats);

        return $this;
    }
}
