<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovieRequest;
use App\Http\Resources\MovieResource;
use App\Repositories\MovieRepository;

class MovieController extends Controller
{
    /**
     * Repository
     *
     * @var MovieRepository
     */
    protected $repository;

    /**
     * MovieController constructor
     *
     * @param MovieRepository $movieRepository
     */
    public function __construct(MovieRepository $movieRepository)
    {
        $this->repository = $movieRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MovieResource::collection($this->repository->index());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MovieRequest $request)
    {
        $movie = $this->repository->create($request->only([
            'title',
            'description'
        ]));

        return new MovieResource($movie);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return new MovieResource($this->repository->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MovieRequest $request, int $id)
    {
        $movie = $this->repository->update($id, $request->only([
            'title',
            'description'
        ]));

        return new MovieResource($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        return $this->respond(([
            'deleted' => $this->repository->delete($id)
        ]));
    }
}
