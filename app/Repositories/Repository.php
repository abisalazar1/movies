<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * Model
     *
     * @var Model
     */
    public $model;

    /**
     * Gets a single record
     *
     * @param  int    $id
     * @return Model
     */
    public function find(?int $id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Creates a model instance and saves it in the datebase
     *
     * @param  array $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Updates a model instace
     *
     * @param  int    $id
     * @param  array  $attibutes
     * @return Model
     */
    public function update(int $id, array $attributes)
    {
        return tap($this->find($id), function ($model) use ($attributes) {
            $model->update($attributes);
        });
    }

    /**
     * Deletes a model instance
     * @param  int     $id
     * @return boolean
     */
    public function delete(int $id)
    {
        return $this->find($id)->delete();
    }
}
