<?php

namespace Modules\Base\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Base\Facade\CrudBuilderFacade;
use PhpOption\Option;

class GeneralController extends Controller
{
    protected $params;

    public function __construct($params)
    {
        $this->params = $params;
    }
    public function fetchData($request, $resourceClass, $resourceName)
    {
        $data_validated = $request->validated();
        return $this->handleCrudOperation(
            operation: function ($data) {
                return CrudBuilderFacade::setParams($data->ToMap())
                    ->setPaginate(Option::fromValue($data->withPagination()))
                    ->setPerPage(Option::fromValue($data->PerPage()))
                    ->build()->getAllDataModel();
            },
            message: __("message.Data Retrieved Successfully for {$resourceName}"),
            resourceClass: $resourceClass,
            isCollection: true,
            data_validated: $data_validated
        );
    }
    public function showData($request, $resourceClass, $resourceName, $identifierKey = 'id')
    {
        $data_validated = $request->validated();

        return $this->handleCrudOperation(
            operation: function ($data) use ($data_validated, $identifierKey) {
                $id = $data_validated[$identifierKey];
                return CrudBuilderFacade::setParams($data->ToMap())->build()->getSingleDataModel($id);
            },
            message: __("message.Data Retrieved Successfully for {$resourceName}"),
            resourceClass: $resourceClass,
            data_validated: $data_validated
        );
    }

    public function storeData($request, $resourceClass, $resourceName)
    {
        $data_validated = $request->validated();
        return $this->handleCrudOperation(
            operation: function ($data) {
                return CrudBuilderFacade::setParams($data->ToMap())->build()->createDataModel($data->toMap());
            },
            message: __("message.Data Stored Successfully for {$resourceName}"),
            resourceClass: $resourceClass,
            data_validated: $data_validated
        );
    }
    public function updateData($request, $resourceClass, $resourceName, $identifierKey = 'id')
    {
        $data_validated = $request->validated();
        return $this->handleCrudOperation(
            operation: function ($data) use ($data_validated, $identifierKey) {
                $id = $data_validated[$identifierKey];

                return CrudBuilderFacade::setParams($data->ToMap())->build()->updateDataModel(id: $id, data: $data->toMap());
            },
            message: __("message.Data Updated Successfully for {$resourceName}"),
            resourceClass: $resourceClass,
            data_validated: $data_validated
        );
    }

    public function deleteData($request, $resourceName, $identifierKey = 'id')
    {
        $data_validated = $request->validated();

        return $this->handleCrudOperation(
            operation: function ($data) use ($data_validated, $identifierKey) {
                $id = $data_validated[$identifierKey];
                return CrudBuilderFacade::setParams($data->ToMap())->build()->deleteDataModel($id);
            },
            message: __("message.Data Deleted Successfully for {$resourceName}")
        );
    }

}
