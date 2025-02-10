<?php

namespace Modules\Base\Repository;


use Illuminate\Support\Facades\DB;

use Modules\Base\Factory\GeneralHandleRelationFactory;

use Modules\Base\Service\GeneralHandleQueryConditionService;
use Modules\Base\Service\GeneralUploadMediaFilesAnd;
use Modules\Base\Service\HandleGeneralCreatedModelService;
use Modules\Base\Service\HandleModelTranslationService;
use PhpOption\Option;

class CrudRepository extends BaseCrudRepository
{

    public function __construct(?array $params = null, ?Option $paginate = null, ?Option $search = null, ?Option $perPage = null)
    {
        parent::__construct($params, $paginate, $search, $perPage);
    }

    public function getAllDataModel()
    {
        $model = resolve($this->params['model']);
        $query = $model->query();
        $query = GeneralHandleQueryConditionService::generalHandleQueryCondition(query: $query, data: $this->params);
        if ($this->paginate->isDefined() && $this->paginate->get()) {
            $perPage = $this->perPage->getOrElse(15);
            $allDataModel = $query->paginate($perPage);
        } else {
            $allDataModel = $query->get();
        }
        return $allDataModel;
    }

    public function getSingleDataModel($id)
    {
        $model = resolve($this->params['model']);
        $singleModel = $model->whereId($id)->first();
        return $singleModel;
    }

    public function createDataModel(array $data)
    {
        $data['is_compatible_update'] = $this->params['is_compatible_update'] ?? false;
        DB::beginTransaction();
        $model = resolve($this->params['model']);
        $data = GeneralUploadMediaFilesAnd::storeOrUpdateMediaFiles(model: $model, data: $data);
        $data = HandleModelTranslationService::handleModelTranslation(model: $model, data: $data);
        $createdModel = HandleGeneralCreatedModelService::handleCreatedModel(model: $model, data: $data);
        GeneralHandleRelationFactory::handleGeneralRelation(params: $this->params, data: $data, createdModel: $createdModel);
//        HandleGeneralModelRelationServices::handleGeneralModelRelation(data: $data, model: $createdModel);
        DB::commit();
        return $createdModel;
    }

    public function updateDataModel($id, array $data)
    {
        DB::beginTransaction();
        $model = resolve($this->params['model']);
        $existingModel = $model->whereId($id)->first();
        $data = GeneralUploadMediaFilesAnd::storeOrUpdateMediaFiles(model: $model, data: $data, existingData: $existingModel, update: true);
        $data = HandleModelTranslationService::handleModelTranslation(model: $model, data: $data);
        $existingModel->update($data);
//        HandleGeneralModelRelationServices::handleGeneralModelRelation(data: $data, model: $existingModel, update: true);
        GeneralHandleRelationFactory::handleGeneralRelation(params: $this->params, data: $data, createdModel: $existingModel, update: true);
        DB::commit();
        return $existingModel;
    }


    public function deleteDataModel($id): bool
    {
        $model = resolve($this->params['model']);
        $model = $model->whereId($id)->first();
        if (!$model) {
            return false;
        }
        GeneralUploadMediaFilesAnd::deleteMediaFiles(model: $model, existingModel: $model);
        $model->whereId($id)->delete();
        return true;
    }

    public function createOrUpdateDataModel(array $data, array $updateData = [])
    {
        $model = resolve($this->params['model']);
        $data = GeneralUploadMediaFilesAnd::storeOrUpdateMediaFiles(model: $model, data: $data);
        $data = HandleModelTranslationService::handleModelTranslation(model: $model, data: $data);
        if (array_key_exists("id", $updateData)) {
            $model = $model->whereId($updateData['id'])->first();
            if (!$model) {
                return $model->create($data);
            }
            return $model->update($data);
        }
        return $model->updateOrCreate($updateData, $data);
    }
}
