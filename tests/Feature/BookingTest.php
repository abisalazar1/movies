<?php

namespace Tests\Feature;

use App\Movie;
use App\Booking;
use App\Customer;
use App\MovieTime;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * customer booking list.
     *
     * @test
     *
     * @return void
     */
    public function can_visit_customer_bookings_index_page()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);


        $response = $this->call('get', 'api/customers/' . $customer->id . '/bookings');


        $response->assertStatus(200);
    }

    /**
     * customer booking list.
     *
     * @test
     *
     * @return void
     */
    public function cant_visit_customer_bookings_index_page_if_customer_does_not_exist()
    {
        $response = $this->call('get', 'api/customers/1/bookigs');


        $response->assertStatus(404);
    }

    /**
     * show a single booking.
     *
     * @test
     *
     * @return void
     */
    public function can_show_a_single_customer_booking()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $movie = factory(Movie::class)->create();
        
        $movieTime = factory(MovieTime::class)->create(['movie_id' => $movie->id]);

        $booking = \factory(Booking::class)->create([
            'movie_time_id' => $movieTime->id,
            'customer_id' => $customer->id
            ]);

        $response = $this->call('get', 'api/customers/' . $customer->id . '/bookings' . '/' . $booking->id);


        $response->assertStatus(200)->assertJson([
            'data' => [
                'id' => $booking->id,
                'seats' => 1
            ]
        ]);
    }

    /**
     * cant show a booking that does not exists .
     *
     * @test
     *
     * @return void
     */
    public function cant_show_a_single_customer_booking_that_doesnt_exists()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $response = $this->call('get', 'api/customers/' . $customer->id . '/bookings' . '/1');


        $response->assertStatus(404);
    }

    /**
     * A movie can be created
     *
     * @test
     *
     * @return void
     */
    public function a_booking_cant_be_without_a_customer_created()
    {
        $this->call('post', 'api/customers/1/bookings', [
            'seats' => 1,
        ]);

        $this->assertDatabaseMissing('bookings', [
            'id' => 1,
            'seats' => 1
        ]);
    }

    /**
     * A booking can be created
     *
     * @test
     *
     * @return void
     */
    public function a_booking_can_be_created()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $movie = factory(Movie::class)->create();
        
        $movieTime = factory(MovieTime::class)->create(['movie_id' => $movie->id]);

        $response = $this->call('post', 'api/customers/' . $customer->id . '/bookings', [
            'seats' => 1,
            'movie_time_id' => $movieTime->id
        ]);

        $this->assertDatabaseHas('bookings', [
            'customer_id' => $customer->id,
            'seats' => 1,
        ]);

        $response->assertJson([
            'data' => [
                'id' => 1,
                'seats' => 1,
            ]
        ]);
    }


    /**
     * A booking cannot be create with more than 10 seats
     *
     * @test
     *
     * @return void
     */
    public function cannot_book_more_than_10_seats()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $movie = factory(Movie::class)->create();
        
        $movieTime = factory(MovieTime::class)->create(['movie_id' => $movie->id]);

        $response = $this->call('post', 'api/customers/' . $customer->id . '/bookings', [
            'seats' => 11,
            'movie_time_id' => $movieTime->id
        ]);

        $this->assertDatabaseMissing('bookings', [
            'customer_id' => $customer->id,
            'seats' => 11,
        ]);

        $response->assertJson([
            'error' => 'Sorry, only 10 seats available'
        ]);
    }

 

    /**
     * A booking can be updated
     *
     * @test
     *
     * @return void
     */
    public function booking_can_be_updated()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $movie = factory(Movie::class)->create();
        
        $movieTime = factory(MovieTime::class)->create(['movie_id' => $movie->id]);

        $booking = \factory(Booking::class)->create([
            'movie_time_id' => $movieTime->id,
            'customer_id' => $customer->id
            ]);

        $response = $this->call('put', 'api/customers/' . $customer->id . '/bookings' . '/' . $booking->id, [
            'seats' => 6,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'customer_id' => $customer->id,
            'seats' => 6,
        ]);

        $response->assertJson([
            'data' => [
                'id' => $booking->id,
                'seats' => 6,
            ]
        ]);
    }

    /**
     * A booking cannot be updated if the total seats if the movie does not have spaces
     *
     * @test
     *
     * @return void
     */
    public function booking_cannot_be_updated_when_there_are_no_spaces()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $movie = factory(Movie::class)->create();
        
        $movieTime = factory(MovieTime::class)->create(['movie_id' => $movie->id]);

        $booking1 = \factory(Booking::class)->create([
            'movie_time_id' => $movieTime->id,
            'customer_id' => $customer->id,
            'seats' => 5,
            ]);

        $booking = \factory(Booking::class)->create([
            'movie_time_id' => $movieTime->id,
            'customer_id' => $customer->id,
            'seats' => 4,
            ]);

        $response = $this->call('put', 'api/customers/' . $customer->id . '/bookings' . '/' . $booking->id, [
            'seats' => 6,
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'customer_id' => $customer->id,
            'seats' => 4,
        ]);

        $response->assertJson([
            'error' => 'Sorry, only 1 seats available'
        ]);
    }

    /**
     * A booking can be deleted
     *
     * @test
     *
     * @return void
     */
    public function booking_can_be_deleted()
    {
        $customer = factory(Customer::class)->create([
            'email' => 'pepe@example.com',
            'name' => 'Pepe Lepew',
        ]);

        $movie = factory(Movie::class)->create();
        
        $movieTime = factory(MovieTime::class)->create(['movie_id' => $movie->id]);

        $booking = \factory(Booking::class)->create([
            'movie_time_id' => $movieTime->id,
            'customer_id' => $customer->id
            ]);

        $this->call('delete', 'api/customers/' . $customer->id . '/bookings' . '/' . $booking->id);

        $this->assertDatabaseMissing('bookings', [
            'id' => $booking->id,
        ]);
    }
}
