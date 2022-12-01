<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeaveStatus extends Enum
{
	const APPLIED	= '1';
	const PASSED	= '2';
	const REJECT	= '3';
}