<?php

namespace App\Repositories;

use App\MovieTime;

class MovieTimeRepository extends Repository
{
    /**
     * MovieTimeRepostiroy Constructor
     *
     * @param MovieTime $movieTime
     */
    public function __construct(MovieTime $movieTime)
    {
        $this->model = $movieTime;
    }

    /**
     * Shows all times from a movie
     *
     * @param int $movieId
     *
     * @return Collection
     */
    public function index(int $movieId)
    {
        return $this->model->where('movie_id', $movieId)->paginate();
    }

    /**
     * Gets the time
     *
     * @param int $movieId
     * @param int $timeId
     *
     * @return Collection
     */
    public function getByMovieIdAndTimeId(int $movieId, int $timeId)
    {
        return $this->model->where('movie_id', $movieId)->findOrFail($timeId);
    }

    /**
     * Gets the time
     *
     * @param int $movieId
     * @param int $timeId
     *
     * @return Collection
     */
    public function deleteFromMovie(int $movieId, int $timeId)
    {
        $model = $this->model->where('movie_id', $movieId)->findOrFail($timeId);

        return $model->delete();
    }
}
