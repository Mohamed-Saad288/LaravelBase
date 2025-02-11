<?php

namespace Modules\General\app\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\General\app\Repositories\Contracts\CurdRepositoryInterface;
use Exception;

class CurdRepository implements CurdRepositoryInterface
{
    /**
     * @var Model
     */
    protected Model $model;

    /**
     * CurdRepository constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a paginated list of records with optional filtering.
     * @param array $data
     * @return Collection|LengthAwarePaginator
     */
    public function index(array $data): Collection|LengthAwarePaginator
    {
        $query = $this->model->query();

        if (!empty($data['search'])) {
            $query->where('name', 'like', '%' . $data['search'] . '%');
        }

        if (!empty($data['filters']) && is_array($data['filters'])) {
            foreach ($data['filters'] as $column => $value) {
                $query->where($column, 'like', '%' . $value . '%');
            }
        }

        if (!empty($data['sort_by'])) {
            $query->orderBy($data['sort_by'], $data['sort_direction'] ?? 'asc');
        }


        return (!isset($data['with_pagination']) || $data['with_pagination'])
            ? $query->paginate($data['per_page'] ?? 10)
            : $query->get();
    }

    /**
     * Store a new record in the database.
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * Retrieve a specific record by ID.
     * @param int $id
     * @return Model
     * @throws Exception
     */
    public function show(int $id): Model
    {
        $record = $this->model->find($id);
        if (!$record) {
            throw new Exception("Record not found.");
        }
        return $record;
    }

    /**
     * Update a specific record.
     * @param array $data
     * @param int $id
     * @return Model
     * @throws Exception
     */
    public function update(array $data, int $id): Model
    {
        $record = $this->model->find($id);
        if (!$record) {
            throw new Exception("Record not found");
        }
        $record->update($data);
        return $record;
    }
    /**
     * Delete a specific record.
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function destroy(int $id): bool
    {
        $record = $this->model->find($id);
        if (!$record) {
            throw new Exception("Record not found.");
        }

        return $record->delete();
    }
}
