<?php

namespace App\Enums;

enum TaskStatusEnum: string
{
    case NEW = 'new';
    case IN_PROGRESS = 'in progress';
    case COMPLETED = 'completed';
}
