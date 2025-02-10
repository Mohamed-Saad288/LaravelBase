<?php

namespace Modules\Base\Repository;

use PhpOption\None;
use PhpOption\Option;

abstract class BaseCrudRepository
{

    protected ?array $params = [];

    protected ?Option $paginate = null;
    protected ?Option $search = null;
    protected ?Option $perPage = null;

    public function __construct(?array $params = null, ?Option $paginate = null, ?Option $search = null, ?Option $perPage = null)
    {
        $this->params = $params;
        $this->search = $search;
        $this->paginate = $paginate ?: None::create();
        $this->perPage = $perPage ?: None::create();
    }

    abstract public function getAllDataModel();

    abstract public function getSingleDataModel($id);

    abstract public function createDataModel(array $data);

    abstract public function updateDataModel($id, array $data);

    abstract public function createOrUpdateDataModel(array $data,array $updateData=[]);

    abstract public function deleteDataModel($id);
}
