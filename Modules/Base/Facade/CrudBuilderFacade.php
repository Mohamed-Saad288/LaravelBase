<?php

namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class CrudBuilderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'crud_builder';
    }
}
