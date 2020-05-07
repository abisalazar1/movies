<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Repositories\BookingRepository;

class CustomerBookingController extends Controller
{

    /**
     * Repository
     *
     * @var BookingRepository
     */
    protected $repository;

    /**
     * CustomerBooking Controller
     *
     * @param BookingRepository $bookingRepository
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->repository = $bookingRepository;
    }

    /**
     * customer bookings index
     *
     * @param int $customerId
     *
     * @return BookingResource
     */
    public function index(int $customerId)
    {
        return BookingResource::collection($this->repository->getByCustomerId($customerId));
    }

    /**
     * Creates a record
     *
     * @param BookingRequest $request
     * @param int $customerId
     *
     * @return BookingResource
     */
    public function store(BookingRequest $request, int $customerId)
    {
        $request->merge(['customer_id' => $customerId]);

        $booking = $this->repository->create($request->only([
            'seats',
            'movie_time_id',
            'customer_id',
        ]));


        return new BookingResource($booking);
    }
    
    /**
     * Display record
     *
     * @param int $customerId
     * @param int $bookingId
     *
     * @return BookingResource
     */
    public function show(int $customerId, int $bookingId)
    {
        $booking = $this->repository->getCustomerBoookingById($customerId, $bookingId);
        
        return new BookingResource($booking);
    }
    
    /**
     * Update
     *
     * @param BookingRequest $request
     * @param int $customerId
     * @param int $bookingId
     *
     * @return
     */
    public function update(
        BookingRequest $request,
        int $customerId,
        int $bookingId
    ) {
        $booking = $this->repository->update($bookingId, $request->only([
                'seats'
            ]));

        return new BookingResource($booking);
    }

    /**
     * Deletes a booking
     *
     * @param int $customerId
     * @param int $bookingId
     *
     * @return void
     */
    public function destroy(int $customerId, int $bookingId)
    {
        return $this->respond([
            'deleted' => $this->repository->deleteFromCustomerId($customerId, $bookingId)
        ]);
    }
}
