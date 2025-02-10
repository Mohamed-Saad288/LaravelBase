<?php

namespace Modules\Base\Service;


class GeneralBuildRelatedDataService
{

//    public static function buildRelatedData(array $data, string $key, string $paramsClass,?int $organization_id =null,?string $creatable_type = null,?int $creatable_id = null): ?array
//    {
//        $relatedData = [];
//
//        if (!empty($data[$key])) {
//            $paramsInstance = new $paramsClass();
//
//            foreach ($data[$key] as $item) {
//                $item['organization_id'] = $organization_id;
//                $item['creatable_type'] = $creatable_type;
//                $item['creatable_id'] = $creatable_id;
//                $relatedData[] = $paramsInstance->BuildBody($item);
//                if (isset($item[$key]) && !empty($item[$key])) {
//                    $relatedData = array_merge($relatedData, self::buildRelatedData($item, $key, $paramsClass, $organization_id, $creatable_type, $creatable_id));
//                }
//            }
//
//            return $relatedData;
//        }
//
//        return null;
//
//    }

    public static function buildRelatedData(array $data, string $key, string $paramsClass, ?int $organization_id = null, ?string $creatable_type = null, ?int $creatable_id = null, ?int $parent_id = null): ?array
    {
        $relatedData = [];

        if (!empty($data[$key])) {
            foreach ($data[$key] as $item) {
                // Instantiate the params class and build its body
                $paramsInstance = new $paramsClass();

                // Assign parent_id to child items
                $item['related_id'] = $parent_id;
                $item['organization_id'] = $organization_id;
                $item['creatable_type'] = $creatable_type;
                $item['creatable_id'] = $creatable_id;

                $builtItem = $paramsInstance->BuildBody($item);
                $relatedData[] = $builtItem;

                // Recursive call for nested items with current item id as parent_id
                if (isset($item[$key]) && !empty($item[$key])) {
                    $nestedItems = self::buildRelatedData($item, $key, $paramsClass, $organization_id, $creatable_type, $creatable_id, $builtItem->option_item_id);
                    if ($nestedItems) {
                        $relatedData = array_merge($relatedData, $nestedItems);
                    }
                }
            }
        }

        return $relatedData ?: null;
    }


    public static function processRelations(?array $paramsArray): array
    {
        $relations = [];
        if (!empty($paramsArray)) {
            foreach ($paramsArray as $param) {
                $relations[] = $param->ToMap();
            }
        }
        return $relations;
    }

//    public static function buildRelatedAttachments(array $data,string $paramsClass,?int $organization_id = null,string $title = null,?int $type = 1,?int $specialise_type = null,?bool $is_base_64 = false): ?array
//    {
//        $relatedData = [];
//
//        if ($is_base_64) {
//            $attachmentPaths = upload_base64_images($data, $title);
//        }else {
//            $attachmentPaths = upload_images(images: $data, folder: $title);
//        }
//
//
//        if (!empty($attachmentPaths)) {
//            foreach ($attachmentPaths as $path) {
//
//                $paramsInstance = new $paramsClass();
//
//                $item = [
//                    'organization_id' => $organization_id,
//                    'file' => $path,
//                    'type' => $type,
//                    'specialise_type' => $specialise_type
//                ];
//
//                // Build the item using the params instance
//                $builtItem = $paramsInstance->BuildBody($item);
//                $relatedData[] = $builtItem;
//            }
//        }
//
//        return $relatedData ?: null;
//    }
    public static function buildRelatedAttachments(
        array $data,
        string $key,
        string $paramsClass,
        ?int $organization_id = null,
        string $title = null,
        ?int $type = 1
    ): ?array {
        $relatedData = [];

        // Ensure $data is an array of images
        foreach ($data[$key] as $file) {

            $attachmentPath = upload_image_base64(image: $file['file'], folder: $title);
            if ($attachmentPath) {
                $paramsInstance = new $paramsClass();

                $fileSizeInBytes = filesize(public_path($attachmentPath));
                $item = [
                    'organization_id' => $organization_id,
                    'file' => $attachmentPath,
                    'alt' => $file['alt'] ?? null,
                    'type' => $type,
                    "special_type" => $file['special_type'] ?? null,
                ];

                // Build the item using the params instance
                $builtItem = $paramsInstance->BuildBody($item);

                $relatedData[] = $builtItem;
            }
        }

        return $relatedData ?: null;
    }


}
