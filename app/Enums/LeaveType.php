<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class LeaveType extends Enum
{
	const SICK_LEAVE				= '1';
	const PERSONAL_LEAVE			= '2';
	const MENSTRUATION_LEAVE		= '3';
	const FUNERAL_LEAVE				= '4';
	const WORKRELATED_SICK_LEAVE	= '5';
	const MATERNITY_LEAVE			= '6';
	const MATERNITY_REST_LEAVE		= '7';
	const PATERNITY_LEAVE			= '8';
	const PRENATAL_VISIT_LEAVE		= '9';
	const FAMILY_CARE_LEAVE			= '10';
	const OFFICIAL_LEAVE			= '11';
	const SPECIAL_LEAVE				= '12';
}