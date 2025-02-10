<?php

namespace Modules\Base\Service;

use Illuminate\Database\Eloquent\Model;

class GeneralHandleUpdatingListService
{

    public static function HandleDeleteList(array $lists, Model $model, string $relationMethod,$relation_id = null): void
    {

        $newList = self::FilterListCanBeUpdated(lists: $lists,relation_id: $relation_id);
        $id = $relation_id;
        $updatingList = $newList['updatingList'];
        $creatingList = $newList['creatingList'];
//        dd($creatingList, $updatingList);

        // Step 1: Retrieve the existing list from the database
        $existingItems = $model->$relationMethod()->pluck('id')->toArray(); // ده مجموعة ال ids الى موجوده فى الداتا بيز

        // Step 2: Separate items based on their presence in existingItems and newList
        $newItemIds=[];
        foreach ($updatingList as $list) {
            $newItemIds[] = $list[$id];
        }
        // Items to delete: In the database but not in the new list
        $itemsToDelete = array_diff($existingItems, $newItemIds);

        // Step 3: Perform the delete operation
        $model->$relationMethod()->whereIn('id', $itemsToDelete)->delete();
    }

    public static function FilterListCanBeUpdated(array $lists, $relation_id): Collection|array
    {
        $id = $relation_id;
        $updatingList = collect($lists)->filter(fn($list) =>isset($list[$id]) && $list[$id] != null);
        $creatingList = collect($lists)->filter(fn($list) => !isset($list[$id]) || $list[$id] == null);
        $data =  [
            'updatingList' => $updatingList,
            'creatingList' => $creatingList
        ];

        return $data;
    }

}
