<?php

namespace App\Repositories;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use SebastianBergmann\CodeCoverage\Node\Builder;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function get($condition = null): Builder
    {
        return $this->model->where($condition);
    }

    public function create($requestData): Model
    {
        return $this->model->create($requestData);
    }

    public function find($id): Model
    {
        return $this->model->find($id);
    }

    public function update($data): void
    {
        $data->save();
    }

    public function delete($data): void
    {
        $data->delete();
    }
}
