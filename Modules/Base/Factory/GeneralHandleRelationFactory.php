<?php

namespace Modules\Base\Factory;



use Modules\Base\Service\HandleGeneralModelRelationServices;
use Modules\Base\Service\HandleGeneralParentModelRelationServices;


class GeneralHandleRelationFactory
{

    public static function handleGeneralRelation($params, $data, $createdModel, $update = false)
    {
        if (array_key_exists("is_for_parent", $params) && $params['is_for_parent'] === true) {
            $parent_relation = $params['parent_relation'];
            $parent_id = $params['parent_id'];
            $column_for_parent = $params['column_for_parent'];
            $table_foreign_id = $params['table_foreign_id'];
            HandleGeneralParentModelRelationServices::handleGeneralModelRelation(params:$params, data: $data, model: $createdModel, parent_relation: $parent_relation, update: $update,
                parent_id: $parent_id, column_for_parent: $column_for_parent, table_foreign_id: $table_foreign_id);
        }elseif (!array_key_exists("is_for_parent", $params)) {
            HandleGeneralModelRelationServices::handleGeneralModelRelation(data: $data, model: $createdModel, update: $update);
        }

    }
}
