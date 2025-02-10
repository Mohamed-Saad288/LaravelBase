<?php

namespace Modules\Base\Service;
use Dflydev\DotAccessData\Data;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Modules\Bases\Service\HandleModelTranslationService;

class HandleGeneralModelRelationServices
{
    public static function handleGeneralModelRelation(?array $data, ?Model $model, bool $update = false): void
    {
        // Get hasMany and many-to-many relations dynamically from the model
        $relations = self::getRelations($model);

        if (!empty($relations)) {
            foreach ($relations as $relation) {
                if (array_key_exists($relation, $data) && !empty($data[$relation])) {
                    $relationType = self::getRelationType($model, $relation);
                    match ($relationType) {
                        'hasMany' => self::handleHasManyRelation($model, $relation, $data[$relation], $update),
                        'belongsToMany' => self::handleBelongsToManyRelation($model, $relation, $data[$relation], $update),
                        default => throw new \InvalidArgumentException("Unsupported relation type: $relationType"),
                    };
                }
            }
        }
    }


    // Handle hasMany relation

    private static function getRelations(?Model $model): array
    {
        return method_exists($model, 'getHasManyRelations') ? $model->getHasManyRelations() : [];
    }


    // Handle belongsToMany relation

    private static function getRelationType(Model $model, string $relation): ?string
    {
        $relationInstance = $model->$relation();
        return match (true) {
            $relationInstance instanceof HasMany || $relationInstance instanceof MorphMany => 'hasMany',
            $relationInstance instanceof BelongsToMany || $relationInstance instanceof MorphToMany => 'belongsToMany',
            default => null,
        };
    }

    private static function handleHasManyRelation(Model $model, string $relation, array $items, $update = false): void
    {
        // Delete the existing relation if it exists
        if ($update === true && $model->$relation()->count() > 0) {
            $model->$relation()->delete();
        }

        foreach ($items as $item) {
            if (isset($item)) {
                $translated_model = resolve($item['model']);
                $item = HandleModelTranslationService::handleModelTranslation(model: $translated_model, data: $item);
                $item = GeneralUploadMediaFilesAnd::storeOrUpdateMediaFiles(model: $translated_model, data: $item);

                if ($update && isset($item[$translated_model->relation_id]) && $item[$translated_model->relation_id] !== null) {
                    GeneralHandleUpdatingListService::HandleDeleteList(lists: $items, model: $model, relationMethod: $relation, relation_id: $translated_model->relation_id);
                    $created_model = $model->$relation()->find($item[$translated_model->relation_id])->update($item);
                } else {
                    $created_model = $model->$relation()->create($item);
                }

                self::handleGeneralModelRelation(data: $item, model: $created_model, update: $update);
            }
        }
    }


//    private static function handleHasManyRelation(Model $model, string $relation, array $items, $update = false): void
//    {
//        if ($update) {
//            self::handleUpdateHasManyRelation(model: $model, relation: $relation);
//        }
//        foreach ($items as $item) {
//            if (isset($item)) {
//                $translated_model = resolve($item['model']);
//                $item = HandleModelTranslationService::handleModelTranslation(model: $translated_model, data: $item);
//                $created_model = $model->$relation()->create($item);
//                self::handleGeneralModelRelation(data: $item, model: $created_model, update: $update);
//            }
//        }
//    }


    public static function handleBelongsToManyRelation(Model $model, string $relation, array $items, $update = false): void
    {
        $items = filterPivotColumn(items: $items, model: $model, relation: $relation);

        if ($update) {
            $model->$relation()->detach();
        }
        $model->$relation()->syncWithoutDetaching($items);
    }
}

