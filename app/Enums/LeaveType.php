<?php

namespace App\Enums;

enum LeaveType: int
{
	const sickLeave				= 30;
	const personalLeave			= 14;
	const menstruationLeave		= 12;
	const funeralLeave			= 0;
	const workrelatedSickLeave	= 0;
	const maternityLeave		= 0;
	const maternityRestLeave	= 30;
	const paternityLeave		= 7;
	const prenatalVisitLeave	= 7;
	const familyCareLeave		= 7;
	const officialLeave			= 0;

	public static function getLimitDayInfo(string $leaveType): array
	{
		static $limitDayInfo;

		if (!isset($limitDayInfo)) {
			$oClass = new \ReflectionClass(__CLASS__);
			$constants = $oClass->getConstants();

			if(!isset($constants[$leaveType])) {
				$limitDayInfo = ['status' => 'error', 'limit_day' => ''];
			} else {
				$limitDayInfo = ['status' => 'success', 'limit_day' => $constants[$leaveType]];
			}
		}

		return $limitDayInfo;
	}
}