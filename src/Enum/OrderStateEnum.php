<?php

namespace App\Enum;

enum OrderStateEnum: string
{
    case Pending = 'Pending';
    case OnHold = 'On Hold';
    case Shipped = 'Shipped';
    case Completed = 'Completed';
    case Canceled = 'Canceled';
}
