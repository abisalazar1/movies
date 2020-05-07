<?php
namespace App\Repositories;

use App\Movie;

class MovieRepository extends Repository
{
    
    /**
     * MovieRepostiroy Constructor
     *
     * @param Movie $movie
     */
    public function __construct(Movie $movie)
    {
        $this->model = $movie;
    }

    /**
     * Index
     *
     * @return Collection
     */
    public function index()
    {
        return $this->model->paginate();
    }
}
