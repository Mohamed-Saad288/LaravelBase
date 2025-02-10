<?php

namespace Modules\Base\Enum;

enum ModelTypeEnum : int
{
    case Created = 1;
    case NotCreated = 2;
    case Deleted = 3;
    case Updated = 4;
    case NotUpdated = 5;
}
