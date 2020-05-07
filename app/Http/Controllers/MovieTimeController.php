<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieTimeRequest;
use App\Http\Resources\MovieTimeResource;
use App\Repositories\MovieTimeRepository;

class MovieTimeController extends Controller
{
    /**
     * Repository
     *
     * @var MovieTimeRepository
     */
    protected $repository;

    /**
     * MovieTimeController constructor
     *
     * @param MovieTimeRepository $movieTimeRepository
     */
    public function __construct(MovieTimeRepository $movieTimeRepository)
    {
        $this->repository = $movieTimeRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $movieId)
    {
        return MovieTimeResource::collection($this->repository->index($movieId));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $movieId
     * @return \Illuminate\Http\Response
     */
    public function store(MovieTimeRequest $request, int $movieId)
    {
        $request->merge(['movie_id' => $movieId]);

        $time = $this->repository->create($request->only([
            'movie_id',
            'date_time'
        ]));

        return new MovieTimeResource($time);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $movieId
     * @param  int  $timeId
     * @return \Illuminate\Http\Response
     */
    public function show(int $movieId, int $timeId)
    {
        return new MovieTimeResource($this->repository->getByMovieIdAndTimeId($movieId, $timeId));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $movieId
     * @param  int  $timeId
     * @return \Illuminate\Http\Response
     */
    public function update(MovieTimeRequest $request, int $movieId, int $timeId)
    {
        $movie = $this->repository->update($timeId, $request->only([
            'date_time',
        ]));

        return new MovieTimeResource($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $movieId, int $timeId)
    {
        return $this->respond(([
            'deleted' => $this->repository->deleteFromMovie($movieId, $timeId)
        ]));
    }
}
