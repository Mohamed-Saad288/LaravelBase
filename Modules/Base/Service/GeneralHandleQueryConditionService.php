<?php

namespace Modules\Base\Service;

class GeneralHandleQueryConditionService
{

//    public static function generalHandleQueryCondition($query,$data)
//    {
//        if (isset($data['conditions']) && is_array($data['conditions']) && !empty($data['conditions'])) {
//            foreach ($data['conditions'] as $condition) {
//                if (isset($condition['field'], $condition['value'])) {
//                    $query = self::handleConditionsFactory(condition: $condition,query:  $query);
//                }
//            }
//        }
//        return $query;
//    }
//
//
//    public static function handleConditionsFactory($condition, $query)
//    {
//        $operator = $condition['operator'] ?? '=';
//        $translation = $condition['translation'] ?? true;
//        $field = $condition['field'];
//        $value = $condition['value'];
//        $relation = $condition['relation'] ?? null;
//        $relationCondition = $condition['relation_condition'] ?? null;
//
//        match ($operator) {
//            'like' => $query = $translation
//                ? $query->whereTranslationLike($field, '%' . $value . '%')
//                : $query->where($field, 'like', '%' . $value . '%'),
//
//            'in' => $query = $query->whereIn($field, $value),
//
//            'notIn' => $query = is_array($value)
//                ? $query->whereNull($field)
//                : throw new \InvalidArgumentException('Value for "notIn" must be an array.'),
//
//
//            'whereHas' => $relation
//                ? $query->whereHas($relation, function ($q) use ($relationCondition) {
//                    if ($relationCondition) {
//                        $q->where($relationCondition['field'], $relationCondition['operator'], $relationCondition['value']);
//                    }
//                })
//                : $query,
//
//            default => $query = $query->where($field, $operator, $value),
//        };
//
//        return $query;
//
//
//
//    }

    public static function generalHandleQueryCondition($query, $data)
    {
        if (isset($data['conditions']) && is_array($data['conditions']) && !empty($data['conditions'])) {
            foreach ($data['conditions'] as $condition) {
                if (isset($condition['field'], $condition['value']) || in_array($condition['operator'], ['null', 'not null'])) {
                    $query = self::handleConditionsFactory(condition: $condition, query: $query);
                }
            }
        }
        return $query;
    }

    public static function handleConditionsFactory($condition, $query)
    {
        $operator = $condition['operator'] ?? '=';
        $translation = $condition['translation'] ?? true;
        $field = $condition['field'];
        $value = $condition['value'] ?? null;
        $relation = $condition['relation'] ?? null;
        $relationCondition = $condition['relation_condition'] ?? null;

        match ($operator) {
            'like' => $query = $translation
                ? $query->whereTranslationLike($field, '%' . $value . '%')
                : $query->where($field, 'like', '%' . $value . '%'),

            'in' => $query = $query->whereIn($field, $value),

            'notIn' => $query = $query->whereNotIn($field, $value),

            '!=' => $query = $query->where($field, '!=', $value),

            'null' => $query = $query->whereNull($field),

            'not null' => $query = $query->whereNotNull($field),

            "between" => $query = $query->whereBetween($field,$value),

            'whereHas' => $relation
                ? $query->whereHas($relation, function ($q) use ($relationCondition) {
                    if ($relationCondition) {
                        $q->where($relationCondition['field'], $relationCondition['operator'], $relationCondition['value']);
                    }
                })
                : $query,

            default => $query = $query->where($field, $operator, $value),
        };

        return $query;
    }

//            $condition = [
//                'operator' => 'whereHas',
//                'relation' => 'comments', // Relation name
//                'relation_condition' => [
            //                'field' => 'approved',
            //                'operator' => '=',
            //                'value' => true,
//                ],
//            ];
}
