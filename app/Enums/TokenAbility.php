<?php

declare(strict_types=1);

namespace App\Enums;

enum TokenAbility: string
{
    case ACCESS_TOKEN = 'access-api';
    case REFRESH_TOKEN = 'refresh-token';
}
