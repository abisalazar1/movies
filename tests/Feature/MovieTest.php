<?php

namespace Tests\Feature;

use App\Movie;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MovieTest extends TestCase
{
    use RefreshDatabase;

    /**
     * movies list.
     *
     * @test
     *
     * @return void
     */
    public function can_visit_movies_index_page()
    {
        $response = $this->call('get', 'api/movies');


        $response->assertStatus(200);
    }

    /**
     * show a single movie.
     *
     * @test
     *
     * @return void
     */
    public function can_show_a_movie_page()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $response = $this->call('get', 'api/movies/' . $movie->id);


        $response->assertStatus(200)->assertJson(['data' => [
            'id' => $movie->id,
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]]);
    }

    /**
     * cant show a movie page that does not exist.
     *
     * @test
     *
     * @return void
     */
    public function cant_show_a_single_movie_that_does_not_exist()
    {
        $response = $this->call('get', 'api/movies/1');

        $response->assertStatus(404);
    }

    /**
     * A movie can be updated
     *
     * @test
     *
     * @return void
     */
    public function movie_can_be_updated()
    {
        $movie = factory(Movie::class)->create([
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);


        $this->call('put', 'api/movies/' . $movie->id, [
            'title' => 'Pepe Eats Chocolate',
            'description' => 'Pepe is a big boy and eats chocolate'
            ]);
            
        $this->assertDatabaseHas('movies', [
            'id' => $movie->id,
            'title' => 'Pepe Eats Chocolate',
            'description' => 'Pepe is a big boy and eats chocolate'
        ]);
    }

    /**
     * A movie can be created
     *
     * @test
     *
     * @return void
     */
    public function movie_can_be_created()
    {
        $this->call('post', 'api/movies', [
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);

        $this->assertDatabaseHas('movies', [
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);
    }

    /**
     * A movie can be deleted
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

        $this->call('delete', 'api/movies/' . $movie->id);

        $this->assertDatabaseMissing('movies', [
            'id' => $movie->id,
            'title' => 'Pepe Adventures',
            'description' => 'Pepe is a big boy and goes to the park',
        ]);
    }
}
