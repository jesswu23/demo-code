<?php

namespace App\Repositories;

use App\Models\Calendar;

class CalendarRepository
{
	public function getByDate( $date = null )
	{
		$model = Calendar::where('date', '=', $date)->first();

		return $model;
	}

	public function updateByDate( $date = null, array $params )
	{
		$model = $this->getByDate( $date );
		$model->is_holiday = $params['is_holiday'];
		$model->memo = $params['memo'];
		$model->save();

		return $model;
	}

	public function getHolidayByDateRange( $start_date = null, $end_date = null)
	{
		$models = Calendar::where('date', '>=', $start_date)->where('date', '<=', $end_date)->where('is_holiday', '=', '2')->get();

		return $models;
	}

	public function events()
	{
		$models = Calendar::where('is_holiday', '=', '2')->whereNotNull('memo')->get();

		return $models;
	}
}
