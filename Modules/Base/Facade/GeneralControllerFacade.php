<?php

namespace Modules\Base\Facade;

use Illuminate\Support\Facades\Facade;

class GeneralControllerFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'GeneralController';
    }
}
