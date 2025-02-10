<?php

namespace Modules\Base\Service;



use Modules\Base\Response\DataFailed;

class GeneralHandleResourceResponseService
{

    public static function handleResourceResponse(?string $resourceClass = null, $ResourceData = null, ?bool $withPagination = false, ?string $message = null, ?bool $collection = true)
    {


        if ($resourceClass == null) {
            return new DataFailed(statusCode:$ResourceData->getStatusCode() , message: $message ?? __('message.Operation Failed'),status: $ResourceData->getStatus());
        }

        $data = $ResourceData->getData();


        $resource = ($collection)
            ? self::handleCollectionResponse($data, $resourceClass, $withPagination)
            : self::handleSingleResponse($data, $resourceClass);

        $ResourceData->setMessage($message ?? __('message.Operation Successful'));
        $ResourceData->setResourceData($resource);
        return $ResourceData;

    }

    private static function handleCollectionResponse($data, $resourceClass, ?bool $withPagination = false)
    {

        // No pagination
        return ($withPagination)
            ? $resourceClass::collection($data)->response()->getData(true)  // Pagination
            : $resourceClass::collection($data);
    }

    private static function handleSingleResponse($data, $resourceClass)
    {
        return new $resourceClass($data);
    }
}
