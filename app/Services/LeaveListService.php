<?php

namespace App\Services;

use App\Repositories\LeaveListRepository;
use App\Repositories\CalendarRepository;

Class LeaveListService
{
	protected $leaveListRepository;
	protected $calendarRepository;

	public function __construct(LeaveListRepository $leaveListRepository, CalendarRepository $calendarRepository) {
		$this->leaveListRepository = $leaveListRepository;
		$this->calendarRepository = $calendarRepository;
	}

	public function create(array $params)
	{
		$params = $this->formatParams($params);
		if(isset($params['status']) && $params['status'] == 'error') {
			return ['status' => 'error', 'message' => $params['message']];
		}

		$this->leaveListRepository->create($params);

		return ['status' => 'success', 'message' => 'Apply success.'];
	}

	public function update(int $id, array $params)
	{
		$params = $this->formatParams($params);
		if(isset($params['status']) && $params['status'] == 'error') {
			return ['status' => 'error', 'message' => $params['message']];
		}

		$this->leaveListRepository->update($id, $params);

		return ['status' => 'success', 'message' => 'Update success'];
	}

	public function updateByDate(string $date)
	{
		// Get data after this date
		$date = date_create($date);
		$date = date_format($date,"Y-m-d");

		$leaveLists = $this->leaveListRepository->getByDate($date);
		foreach ($leaveLists as $key => $leaveList) {
			$total_hours = $this->getLeaveHours($leaveList['start_at'], $leaveList['end_at']);

			if(isset($total_hours['status']) && $total_hours['status'] == 'error') {
				$params['type'] = 3; // reject
			}

			$params['hours'] = $total_hours;

			$this->leaveListRepository->update($leaveList->id, $params);
		}

		return ['status' => 'success', 'message' => 'Update success'];

	}

	public function get(int $id)
	{
		$leaveList = $this->leaveListRepository->get($id);

		$start_array = explode(' ', $leaveList['start_at']);
		$leaveList->start_date = (isset($start_array[0])) ? $start_array[0] : '';
		$leaveList->start_time = (isset($start_array[1])) ? date_format(date_create($start_array[1]), 'H:i') : '';

		$end_array = explode(' ', $leaveList['end_at']);
		$leaveList->end_date = (isset($end_array[0])) ? $end_array[0] : '';
		$leaveList->end_time = (isset($end_array[1])) ? date_format(date_create($end_array[1]), 'H:i') : '';

		return $leaveList;
	}

	public function events()
	{
		$events = [];

		// Get calendar event
		$calendars = $this->calendarRepository->events();
		foreach ($calendars as $key => $calendar) {
			$tmp['title'] = ($calendar->memo) ? $calendar->memo : '';

			$date = date_create($calendar->date);
			$tmp['start'] = date_format($date,"Y-m-d");
			$tmp['color'] = '#dc3545';

			$events[] = $tmp;
		}

		// Get leave list
		$leaveLists = $this->leaveListRepository->lists();
		foreach ($leaveLists as $key => $leaveList) {
			$tmp['title'] = $leaveList->user->name;
			$tmp['start'] = $leaveList->start_at;
			$tmp['end'] = $leaveList->end_at;
			$tmp['url'] = url('leave/edit/' . $leaveList->id);
			$tmp['color'] = '#0d6efd';

			$events[] = $tmp;
		}

		return response()->json($events, 200);
	}

	protected function getLeaveHours(string $start_date, string $end_date)
	{
		$start_date_create = date_create($start_date);
		$end_date_create = date_create($end_date);

		$diff = date_diff($start_date_create, $end_date_create);
		$diff_day = $diff->d;
		$diff_hour = $diff->h;

		// Check if it's a holiday
		$start_date = date_format($start_date_create, "Ymd"); // The database format is YYYYMMDD
		$start_hour = date_format($start_date_create, "H");
		$end_date = date_format($end_date_create, "Ymd"); // The database format is YYYYMMDD
		$end_hour = date_format($end_date_create, "H");

		$checkResult = $this->calendarRepository->getHolidayByDateRange($start_date, $end_date);
		$holiday_hours = ($diff_day > 0) ? ($checkResult->count() * 8) : 0;

		$hour = $diff_hour;
		if($start_hour <= 12 && $end_hour > 12) {
			$hour = $diff_hour - 1; // minus 12:00 ~ 13:00
		}

		if($diff_day > 0 && $diff_hour < 9){
			$hour = $diff_hour;
		} else if($diff_day == 0 && $diff_hour > 9) {
			// minus 18:00 ~ 9:00 hour
			$hour = $diff_hour - 15;
		}

		$total_hours = ($diff_day * 8) + $hour - $holiday_hours;

		return $total_hours;
	}

	protected function combineDateTime(string $start_date, string $start_time, string $end_date, string $end_time)
	{
		$start_datetime = $start_date . ' ' . $start_time;
		$end_datetime = $end_date . ' ' . $end_time;

		return [$start_datetime, $end_datetime];
	}

	protected function formatParams(array $params)
	{
		list($start_datetime, $end_datetime) = $this->combineDateTime($params['start_date'], $params['start_time'], $params['end_date'], $params['end_time']);
		$total_hours = $this->getLeaveHours($start_datetime, $end_datetime);

		if(isset($total_hours['status']) && $total_hours['status'] == 'error') {
			return ['status' => 'error', 'message' => $total_hours['message']];
		}

		$params['hours'] = $total_hours;
		$params['start_at'] = $start_datetime;
		$params['end_at'] = $end_datetime;

		return $params;
	}
}