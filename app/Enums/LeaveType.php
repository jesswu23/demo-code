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

	public static function getLeaveTypeName(): array
	{
		return [
			self::SICK_LEAVE				=> '病假',
			self::PERSONAL_LEAVE			=> '事假',
			self::MENSTRUATION_LEAVE		=> '生理假',
			self::FUNERAL_LEAVE				=> '喪假',
			self::WORKRELATED_SICK_LEAVE	=> '公傷病假',
			self::MATERNITY_LEAVE			=> '產假',
			self::MATERNITY_REST_LEAVE		=> '安胎休養假',
			self::PATERNITY_LEAVE			=> '陪產假',
			self::PRENATAL_VISIT_LEAVE		=> '產檢假',
			self::FAMILY_CARE_LEAVE			=> '家庭照顧假',
			self::OFFICIAL_LEAVE			=> '公假',
			self::SPECIAL_LEAVE				=> '特別休假'
		];
	}
}