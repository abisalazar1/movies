<?php

namespace Tests\Feature;

use App\Movie;
use App\MovieTime;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTimeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * movie times list.
     *
     * @test
     *
     * @return void
     */
    public function can_view_movie_times_index_page()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $response = $this->call('get', 'api/movies/' . $movie->id);

        $response->assertStatus(200);
    }

    /**
     * show a single movie. time
     *
     * @test
     *
     * @return void
     */
    public function can_show_a_movie_time()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $movieTime = factory(MovieTime::class)->create([
            'movie_id' => $movie->id
        ]);

        $response = $this->call('get', "api/movies/{$movie->id}/times/{$movieTime->id}");


        $response->assertStatus(200)->assertJson(['data' => [
            'id' => $movieTime->id,

        ]]);
    }

    /**
     * cant show a movie time page that does not exist.
     *
     * @test
     *
     * @return void
     */
    public function cant_show_a_single_movie_time_that_does_not_exist()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $response = $this->call('get', 'api/movies/'. $movie->id . '/times/1');

        $response->assertStatus(404);
    }

    /**
     * A movie time can be updated
     *
     * @test
     *
     * @return void
     */
    public function movie_time_can_be_updated()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $movieTime = factory(MovieTime::class)->create([
            "date_time" => "2020-01-05 17:00",
            "movie_id" => $movie->id,
        ]);


        $this->call('put', "api/movies/{$movie->id}/times/{$movieTime->id}", [
            "date_time" => "2020-05-05 20:00",
            ]);
            
        $this->assertDatabaseHas('movie_times', [
            'id' => $movieTime->id,
            "date_time" => "2020-05-05 20:00",
        ]);
    }

    /**
     * A movie time can be created
     *
     * @test
     *
     * @return void
     */
    public function movie_time_can_be_created()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $this->call('post', 'api/movies/' . $movie->id . '/times', [
            "date_time" => "2020-01-05 17:00",
        ]);

        $this->assertDatabaseHas('movie_times', [
            "date_time" => "2020-01-05 17:00",
            'movie_id' => $movie->id,
        ]);
    }

    /**
     * A movie time can be deleted
     *
     * @test
     *
     * @return void
     */
    public function movie_can_be_deleted()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);
        $movieTime = factory(MovieTime::class)->create([
            "date_time" => "2020-01-05 17:00",
            "movie_id" => $movie->id,
        ]);
        $this->call('delete', 'api/movies/' . $movie->id . '/times//' . $movieTime->id);

        $this->assertDatabaseMissing('movies', [
            'id' => $movieTime->id,
            "date_time" => "2020-01-05 17:00",
         
        ]);
    }
}
