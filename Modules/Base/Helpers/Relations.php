<?php


use Illuminate\Support\Facades\Schema;

function filterPivotColumn($items, $model, $relation): array
{

    $pivotTable = $model->$relation()->getTable();

    // Get the columns of the pivot table
    $pivotColumns = Schema::getColumnListing($pivotTable);

    // Filter each item to include only pivot table columns
    $filteredItems = array_map(function ($item) use ($pivotColumns) {
        return array_intersect_key($item, array_flip($pivotColumns));
    }, $items);

    return $filteredItems;
}
