<?php

use App\Movie;
use App\MovieTime;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Movie::class, 50)->create()->each(function ($movie) {
            factory(MovieTime::class, 30)->create(['movie_id' => $movie->id]);
        });
    }
}
