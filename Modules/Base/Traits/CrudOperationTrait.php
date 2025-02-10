<?php

namespace Modules\Base\Traits;

use Exception;
use Modules\Base\Service\GeneralHandleResourceResponseService;

trait CrudOperationTrait
{
    public function handleCrudOperation(callable $operation, string $message, string $resourceClass = null, bool $isCollection = false,?array $data_validated = [])
    {
//        try {
            $data = $this->params->BuildBody($data_validated);
            $result = $operation($data);
            $withPagination = $data->withPagination();
                return GeneralHandleResourceResponseService::handleResourceResponse(
                    resourceClass: $resourceClass,
                    ResourceData: $result,
                    withPagination: $withPagination,
                    message: $message,
                    collection: $isCollection
                )->response();
//        } catch (Exception $exception) {
//            return $this->returnException(
//                $exception->getMessage() . " in the file " . $exception->getFile() . " in line " . $exception->getLine(),
//                500
//            );
//        }
    }

}
