<?php

namespace App\Repositories;

use App\Booking;

class BookingRepository extends Repository
{
    /**
     * BookingRepostiroy Constructor
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking)
    {
        $this->model = $booking;
    }

    /**
     * Gets all booking from a specific customer
     *
     * @param int $customerId
     *
     * @return Collection
     */
    public function getByCustomerId(int $customerId)
    {
        return $this->model->where('customer_id', $customerId)->paginate();
    }
    
    /**
     * Gets a single booking from a specific customer
     *
     * @param int $customerId
     * @param int $bookingId
     *
     * @return Booking
     */
    public function getCustomerBoookingById(int $customerId, int $bookingId)
    {
        return $this->model->where('customer_id', $customerId)->findOrFail($bookingId);
    }
    
    /**
     * Deletes a booking from a specific customer
     *
     * @param int $customerId
     * @param int $bookingId
     *
     * @return bool
     */
    public function deleteFromCustomerId(int $customerId, int $bookingId)
    {
        return $this->model->where('customer_id', $customerId)->findOrFail($bookingId)->delete();
    }
}
