<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeavePeriod extends Enum
{
    const MORNING_START_HOUR = 9;
    const MORNING_END_HOUR = 13;
    const AFTERNOON_START_HOUR = 14;
    const AFTERNOON_END_HOUR = 18;
}
