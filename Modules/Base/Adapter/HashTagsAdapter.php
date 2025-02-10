<?php

namespace Modules\Base\Adapter;

class HashTagsAdapter
{

    public static function adapt(array $data): array
    {
        $ids = $data;
        $hash_tags = []; // Initialize the array properly
        foreach ($ids as $id) {
            $hash_tags[] = ['hash_tag_id' => $id]; // Append each id with key 'option_id'
        }
        $hash_tags['hash_tag_ids'] = $hash_tags;

        return $hash_tags;
    }
}
