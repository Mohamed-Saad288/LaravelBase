<?php

namespace Modules\Base\Adapter;

class GeneralPivotAdapter
{

    public static function adapt(array $data, string $key): array
    {
        $result = [];
        if (empty($data)) {
            return $result;
        }
        foreach ($data as $id) {
            $result[] = [$key => $id]; // Add each ID with the provided key
        }
        $result["{$key}s"] = $result; // Add the full list with a pluralized key

        return $result;
    }

}
