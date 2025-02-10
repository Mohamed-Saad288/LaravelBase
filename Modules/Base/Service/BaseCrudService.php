<?php

namespace Modules\Base\Service;


use Modules\Base\Repository\BaseCrudRepository;

abstract class BaseCrudService
{
    protected BaseCrudRepository $repository;
    public function __construct(BaseCrudRepository $repository)
    {
        $this->repository = $repository;
    }

    abstract public function getAllDataModel();

    abstract public function getSingleDataModel($id);

    abstract public function createDataModel(array $data);

    abstract public function updateDataModel($id, array $data);

    abstract public function createOrUpdateDataModel(array $data);

    abstract public function deleteDataModel($id);
}
