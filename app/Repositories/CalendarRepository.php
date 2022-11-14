<?php

namespace App\Repositories;

use App\Models\Calendar;

class CalendarRepository
{
	public function getByDate(int $date)
	{
		$calendar = Calendar::where('date', '=', $date)->first();

		return $calendar;
	}

	public function updateByDate(int $date, array $params)
	{
		$calendar = $this->getByDate( $date );
		$calendar->is_holiday = $params['is_holiday'];
		$calendar->memo = $params['memo'];
		$calendar->save();

		return $calendar;
	}

	public function getHolidayByDateRange(int $start_date, int $end_date)
	{
		$calendars = Calendar::where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('is_holiday', '=', '2')->get();

		return $calendars;
	}

	public function events()
	{
		$calendars = Calendar::where('is_holiday', '=', '2')->whereNotNull('memo')->get();

		return $calendars;
	}
}
