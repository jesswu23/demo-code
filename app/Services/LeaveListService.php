<?php

namespace App\Services;

use App\Repositories\LeaveListRepository;
use App\Repositories\CalendarRepository;

Class LeaveListService
{
	protected $leaveListRepository;
	protected $calendarRepository;

	public function __construct( LeaveListRepository $leaveListRepository, CalendarRepository $calendarRepository ) {
		$this->leaveListRepository = $leaveListRepository;
		$this->calendarRepository = $calendarRepository;
	}

	public function create(array $params)
	{
		$total_hours = $this->getLeaveHours( $params['start_date'], $params['end_date']);

		$result = $this->leaveListRepository->create([
			'user_id' => $params['user_id'],
			'start_at' => $params['start_date'],
			'end_at' => $params['end_date'],
			'hours' => $total_hours,
			'type' => $params['type'],
			'reason' => $params['reason']
		]);

		return ['status' => 'success', 'message' => 'Apply success.'];
	}

	public function update($id = null, array $params)
	{
		$total_hours = $this->getLeaveHours( $params['start_date'], $params['end_date']);
		$params['hours'] = $total_hours;

		$model = $this->leaveListRepository->update($id, $params);

		return $model;
	}

	public function updateByDate( $date = null)
	{
		// Get data after this date
		$date = date_create( $date );
		$date = date_format($date,"Y-m-d");

		$models = $this->leaveListRepository->getByDate( $date );
		foreach ($models as $key => $model) {
			$total_hours = $this->getLeaveHours( $model['start_at'], $model['end_at']);
			$params['hours'] = $total_hours;

			$this->leaveListRepository->update($model->id, $params);
		}

		return true;

	}

	public function get( $id = null)
	{
		if( !$id ) {
			return false;
		}

		$model = $this->leaveListRepository->get( $id );

		return $model;
	}

	public function events()
	{
		$events = [];

		// Get calendar event
		$models = $this->calendarRepository->events();
		foreach ($models as $key => $model) {
			$tmp['title'] = $model->memo;

			$date = date_create( $model->date );
			$tmp['start'] = date_format($date,"Y-m-d");

			$events[] = $tmp;
		}

		// Get leave list
		$models = $this->leaveListRepository->lists();
		foreach ($models as $key => $model) {
			$tmp['title'] = $model->user->name;
			$tmp['start'] = $model->start_at;
			$tmp['end'] = $model->end_at;
			$tmp['url'] = url('leave/edit/' . $model->id);

			$events[] = $tmp;
		}

		return response()->json($events, 200);
	}

	protected function getLeaveHours( $start_date = null, $end_date = null)
	{
		$start_date = date_create($start_date);
		$end_date = date_create($end_date);

		$diff = date_diff($start_date, $end_date);
		$diff_day = $diff->d;
		$diff_hour = $diff->h;

		// Check if it's a holiday
		$start_date = date_format( $start_date, "Ymd" ); // The database format is YYYYMMDD
		$end_date = date_format( $end_date, "Ymd" ); // The database format is YYYYMMDD

		$checkResult = $this->calendarRepository->getHolidayByDateRange( $start_date, $end_date );
		$holiday_hours = ( $diff_day > 0 ) ? ($checkResult->count() * 7) : 0;

		$hour = $diff->h;
		if($diff_hour < 4) {
			return ['status' => 'error', 'message' => 'Take at least four hours of leave.'];
		} else if( $diff_day > 0 && $diff_hour < 9){
			$hour = $diff_hour;
		} else if($diff_day == 0 && $diff_hour > 9) {
			// minus 18:00 ~ 9:00 hour
			$hour = $diff_hour - 15;
		}

		$total_hours = ($diff_day * 7) + $hour - $holiday_hours;

		return $total_hours;
	}
}