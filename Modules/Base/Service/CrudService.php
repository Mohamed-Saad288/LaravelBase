<?php

namespace Modules\Base\Service;


use Modules\Base\Repository\CrudRepository;
use Modules\Base\Response\DataFailed;
use Modules\Base\Response\DataSuccess;
use PhpOption\None;
use PhpOption\Option;

class CrudService extends BaseCrudService
{

    protected ?array $params = [];
    protected ?Option $paginate = null;
    protected ?Option $search = null;
    protected ?Option $perPage = null;

    public function __construct(?array $params = null, ?Option $paginate = null, ?Option $search = null, ?Option $perPage = null)
    {
        parent::__construct(new CrudRepository($params, $paginate, $search, $perPage));
        $this->params = $params;
        $this->search = $search;
        $this->paginate = $paginate ?: None::create();
        $this->perPage = $perPage ?: None::create();
    }

    public function getAllDataModel()
    {
        $allDataModel = $this->repository->getAllDataModel();
        return new DataSuccess(data: $allDataModel);
    }

    public function getSingleDataModel($id)
    {
        $singleModel = $this->repository->getSingleDataModel($id);
        return new DataSuccess(data: $singleModel);
    }

    public function createDataModel(array $data)
    {
        $createdModel = $this->repository->createDataModel($data);
        return new DataSuccess(data: $createdModel);
    }

    public function updateDataModel($id, array $data)
    {
        $updatedModel = $this->repository->updateDataModel($id, $data);
        return new DataSuccess(data: $updatedModel);
    }

    public function deleteDataModel($id)
    {
       $status = $this->repository->deleteDataModel($id);
       if ($status) {
           return new DataSuccess();
       }
        return new DataFailed();
    }

    public function createOrUpdateDataModel(array $data,array $updateData=[] )
    {
        $createdModel = $this->repository->createOrUpdateDataModel(data:$data,updateData:$updateData);
        return new DataSuccess(data: $createdModel);
    }
}
