<?php

namespace Modules\Base\Service;

class HandleModelTranslationService
{

    public static function handleModelTranslation($model, $data)
    {
        if (property_exists($model, "translatedAttributes") && isset($data['translatedAttributes'])) {
            $flattened = array_merge($data, $data['translatedAttributes']);
            unset($flattened['translatedAttributes']);

            $data = $flattened;
        }
        return $data;
    }

}


        // old code for handle translations

//            $translationFields = $model->translatedAttributes;
//            $supportedLanguages = LaravelLocalization::getSupportedLocales();
//
//            foreach ($supportedLanguages as $localeCode => $properties) {
//                foreach ($translationFields as $field) {
//                    if (isset($data[$field . '_' . $localeCode])) {
//                        $data[$localeCode][$field] = $data[$field . '_' . $localeCode];
//                    }
//                }
//            }
//            // Remove translation fields from main data
//            foreach ($translationFields as $field) {
//                foreach ($supportedLanguages as $localeCode => $properties) {
//                    unset($data[$field . '_' . $localeCode]);
//                }
//            }
