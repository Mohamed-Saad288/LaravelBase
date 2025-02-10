<?php

namespace Modules\Base\Service;

use Illuminate\Database\Eloquent\Model;

class HandleGeneralParentModelRelationServices
{
    public static function handleGeneralModelRelation(?array $params, ?array $data, ?Model $model, string $parent_relation, bool $update = false, ?string $parent_id = null, ?string $column_for_parent = null,
                                                      ?string $table_foreign_id = null): void
    {
        if (!$model || !$data) {
            return;
        }

        $relations = self::getRelations($model);

        foreach ($relations as $relation) {
            if (!isset($data[$relation])) {
                continue;
            }

            if ($relation != $parent_relation) {
                self::handleSimpleBelongsToManyRelation($model, $relation, $data[$relation], $update);
                continue;
            }

            self::createAndLinkItems($model, $relation, $data[$relation], $column_for_parent, $parent_id, $table_foreign_id, $params);
        }
    }

    private static function getRelations(?Model $model): array
    {
        return method_exists($model, 'getHasManyRelations') ? $model->getHasManyRelations() : [];
    }

    private static function handleSimpleBelongsToManyRelation(Model $model, string $relation, array $items, bool $update = false): void
    {
        $model->$relation()->detach();
        $model->$relation()->sync($items);
    }

    private static function createAndLinkItems(Model $model, string $relation, array $items, ?string $column_for_parent, ?string $parent_id, ?string $table_foreign_id, ?array $params): void
    {
        $model->$relation()->detach();

        foreach ($items as $item) {
            if (!isset($item[$column_for_parent])) {
                continue;
            }

            $pivotData = array_merge(
                [
                    $table_foreign_id => $params[$table_foreign_id],
                    'organization_id' => $params['organization_id']
                ],
                $item
            );

            unset($pivotData[$column_for_parent], $pivotData[$parent_id]);

            $model->$relation()->attach($item[$column_for_parent], $pivotData);

            $createdItem = $model->$relation()->wherePivot($column_for_parent, $item[$column_for_parent])
                ->wherePivot('organization_id', $params['organization_id'])
                ->wherePivot($table_foreign_id, $params[$table_foreign_id])
                ->first()
                ->pivot;

            $createdItems[$item[$column_for_parent]] = $createdItem->id;

            if (isset($item[$parent_id])) {
                if (isset($createdItems[$item[$parent_id]])) {
                    $createdItem->related_id = $createdItems[$item[$parent_id]];
                    $createdItem->save();
                }
            }
        }
    }
}
