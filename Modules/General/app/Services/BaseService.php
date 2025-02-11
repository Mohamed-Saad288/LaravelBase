<?php

namespace Modules\General\app\Services;
use Modules\General\app\Repositories\Eloquent\CurdRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class BaseService
{
    protected CurdRepository $repository;

    public function __construct(Model $model)
    {
        $this->repository = new CurdRepository($model);
    }

    public function index(array $data): Collection|LengthAwarePaginator
    {
        return $this->repository->index($data);
    }

    public function store(array $data): Model
    {
        return $this->repository->store($data);
    }

    public function show(int $id): Model
    {
        return $this->repository->show($id);
    }

    public function update(array $data, int $id): Model
    {
        return $this->repository->update($data, $id);
    }

    public function destroy(int $id): bool
    {
        return $this->repository->destroy($id);
    }
}

