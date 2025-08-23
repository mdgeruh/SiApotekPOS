<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


abstract class BaseRepository
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get all records
     */
    public function all(): Collection
    {
        return $this->model->all();
    }



    /**
     * Find record by ID
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * Find record by ID or fail
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create new record
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Update record
     */
    public function update(Model $model, array $data): bool
    {
        return $model->update($data);
    }

    /**
     * Delete record
     */
    public function delete(Model $model): bool
    {
        return $model->delete();
    }

    /**
     * Get records by condition
     */
    public function where(string $column, $value): Collection
    {
        return $this->model->where($column, $value)->get();
    }

    /**
     * Get first record by condition
     */
    public function whereFirst(string $column, $value): ?Model
    {
        return $this->model->where($column, $value)->first();
    }

    /**
     * Get records with relationships
     */
    public function with(array $relationships): Collection
    {
        return $this->model->with($relationships)->get();
    }


}
