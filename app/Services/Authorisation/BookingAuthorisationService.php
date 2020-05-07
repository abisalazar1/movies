<?php
namespace App\Sevices\Authorisation;

use App\Booking;
use App\Exceptions\AuthorisationException;
use App\MovieTime;
use App\Repositories\BookingRepository;
use App\Repositories\MovieTimeRepository;

class BookingAuthorisationService
{
    /**
     * bookingRepository
     *
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * MovieTimeRepository
     *
     * @var MovieTimeRepository
     */
    protected $movieTimeRepository;

    /**
     * MovieTime
     *
     * @var MovieTime
     */
    protected $movieTime;

    /**
     * Booking
     *
     * @var Booking
     */
    protected $booking;

    /**
     * BookignAuhorisationService Controller
     *
     * @param BookingRepository $bookingRepository
     * @param MovieTimeRepository $movieTimeRepository
     */
    public function __construct(BookingRepository $bookingRepository, MovieTimeRepository $movieTimeRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->movieTimeRepository = $movieTimeRepository;
    }

    /**
     * Sets the booking
     *
     * @param Booking $booking
     *
     * @return self
     */
    public function setBooking(Booking $booking)
    {
        $this->booking = $booking;

        $this->setMovieTimeById($booking->movie_time_id);

        return $this;
    }

    /**
     * finds and sets the booking
     *
     * @param int|null $bookingId
     *
     * @return self
     */
    public function setBookingById(?int $bookingId)
    {
        $this->setBooking($this->bookingRepository->find($bookingId));

        return $this;
    }

    /**
     * Sets the movie time
     *
     * @param MovieTime $movieTime
     *
     * @return self
     */
    public function setMovieTime(MovieTime $movieTime)
    {
        $this->movieTime = $movieTime;

        return $this;
    }

    /**
     * finds and sets the movie time
     *
     * @param int|null $movieTime
     *
     * @return self
     */
    public function setMovieTimeById(?int $movieTimeId)
    {
        $this->setMovieTime($this->movieTimeRepository->find($movieTimeId));

        return $this;
    }

    /**
     * Checks the number of seats
     *
     * @return self
     */
    public function checkSeats(?int $seats = 1)
    {
        if (!$this->movieTime->hasSeatsAvailable()) {
            $this->newException('Sorry, there are not seats available');
        }

        $seatsAvailable = $this->movieTime->seatsAvailable();
        
        if ($seatsAvailable < $seats) {
            $this->newException('Sorry, only ' . $seatsAvailable . ' seats available');
        }
        
        return $this;
    }
    
    /**
     * Checks the number of seats
     *
     * @return self
     */
    public function canUpdateSeats(?int $seats = 1)
    {
        /**
         * if the old number of seats is less than the current number of seats
         * it means that thy can proccess as they are decreasing the number
         */
        if ($this->booking->seats >= $seats) {
            return $this;
        }

        /**
         * Checks if the new number of seats is grater than the current number if it is
         * and there are no seats available it will fail
         */
        if (!$this->movieTime->hasSeatsAvailable()) {
            $this->newException('Sorry, there are not seats available');
        }

        $seatsAvailable = $this->movieTime->seatsAvailable();

        if ($seatsAvailable < ($seats - $this->booking->seats)) {
            $this->newException('Sorry, only ' . $seatsAvailable . ' seats available');
        }
        
        return $this;
    }
    
    /**
     * new authorisation exception
     *
     * @param string $message
     *
     * @throws AuthorisationException
     */
    public function newException(string $message)
    {
        throw new AuthorisationException($message);
    }
}
