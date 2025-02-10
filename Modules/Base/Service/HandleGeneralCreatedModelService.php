<?php

namespace Modules\Base\Service;



use Modules\Base\Enum\ModelTypeEnum;

class HandleGeneralCreatedModelService
{

    public static function handleCreatedModel($model, $data)
    {
        $modelType = $data['model_type'] ?? null;
        $modelId = $data['model_id'] ?? null;

        if (isset($modelType) && $modelType == ModelTypeEnum::Created->value && $modelId) {
            $model = $model->whereId($modelId)->first();
            if ($model && isset($data['is_compatible_update']) && $data['is_compatible_update']) {
                $model->update($data);
            }
            return $model;
        }

        return $model->create($data);
    }

}
