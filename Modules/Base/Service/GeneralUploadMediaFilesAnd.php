<?php

namespace Modules\Base\Service;


class GeneralUploadMediaFilesAnd
{
    public static function storeOrUpdateMediaFiles($model, array $data, $existingData = null, $directory = 'AuthImages', $update = false): array
    {
        // Check if the model has a 'media_fields' property
        if (!property_exists($model, 'media_fields')) {
            return $data;
        }
        foreach ($model->media_fields as $field) {
            $directory = $model->directory;

            // If the field exists in the data and is a file
            if (isset($data[$field])) {
                $old_image = $existingData && !empty($existingData->$field) ? $existingData->$field : null;
                // If updating, delete the existing file
                if ($existingData && !empty($existingData->$field)) {
                    self::deleteMediaFile($existingData->$field);

                }

                // Upload the new file
                $data[$field] = self::uploadMediaFile($data[$field], $directory);
                if ($update === true) {
//                    deleteImageFromDisk(disk: 'uploads',old_image:  $old_image);
                    self::deleteMediaFile($data[$field]);
                }
            }


        }
        return $data;
    }

    protected static function deleteMediaFile($filePath): void
    {
        delete_image($filePath);
    }

//    protected static function uploadMediaFile($file, $directory): string
//    {
//        return uploadImage(name: $file, title: $directory);
//    }

    protected static function uploadMediaFile($file, $directory): string
    {
        return upload_image(image: $file, folder: $directory);
    }

    public static function deleteMediaFiles($model, $existingModel)
    {
        if (isset($model->media_fields )){
            foreach ($model->media_fields as $field) {
                if ($existingModel && !empty($existingModel->$field)) {
                    self::deleteMediaFile($existingModel->$field);
                }
            }
        }

    }
}
