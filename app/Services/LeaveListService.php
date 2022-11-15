<?php

namespace App\Services;

use App\Repositories\LeaveListRepository;
use App\Repositories\CalendarRepository;

Class LeaveListService
{
	protected $leaveListRepository;
	protected $calendarRepository;

	public function __construct(LeaveListRepository $leaveListRepository, CalendarRepository $calendarRepository)
	{
		$this->leaveListRepository = $leaveListRepository;
		$this->calendarRepository = $calendarRepository;
	}

	public function create(array $params)
	{
		$params = $this->formatParams($params);
		$result = $this->leaveListRepository->create($params);

		return $result;
	}

	public function update(int $id, array $params)
	{
		$params = $this->formatParams($params);
		$result = $this->leaveListRepository->update($id, $params);

		return $result;
	}

	public function updateByDate(string $date)
	{
		// Get data after this date
		$date = date_create($date);
		$date = date_format($date, "Y-m-d");

		$leaveLists = $this->leaveListRepository->getByDate($date);
		foreach ($leaveLists as $key => $leaveList) {
			$total_hours = $this->getLeaveHours($leaveList['start_at'], $leaveList['end_at']);

			if(isset($total_hours['status']) && $total_hours['status'] == 'error') {
				$params['status'] = 3; // reject
			}

			$params['hours'] = $total_hours;

			$this->leaveListRepository->update($leaveList->id, $params);
		}

		return true;
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

	/**
	 * Get holiday and leave data
	 * @return json Holiday and leave data information
	 */
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

	/**
	 * Calculate leave hours
	 * @param  string $start_date leave start datetime
	 * @param  string $end_date   leave end datetime
	 * @return int             leave hours
	 */
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
		$holiday_hours = ($diff_day >= 0) ? ($checkResult->count() * 8) : 0;

		$hour = $diff_hour - 1; // minus 13:00 ~ 14:00

		$total_hours = ($diff_day * 8) + $hour - $holiday_hours;

		return $total_hours;
	}

	/**
	 * combine leave date and leave time
	 * @param  string $start_date leave start date
	 * @param  string $start_time leave start time
	 * @param  string $end_date   leave end date
	 * @param  string $end_time   leave end time
	 * @return array             leave start datetime and leave end datetime
	 */
	protected function combineDateTime(string $start_date, string $start_time, string $end_date, string $end_time)
	{
		$start_datetime = $start_date . ' ' . $start_time;
		$end_datetime = $end_date . ' ' . $end_time;

		return [$start_datetime, $end_datetime];
	}

	/**
	 * processing parameters
	 * @param  array  $params parameters
	 * @return array          parameters array
	 */
	protected function formatParams(array $params)
	{
		list($start_datetime, $end_datetime) = $this->combineDateTime($params['start_date'], $params['start_time'], $params['end_date'], $params['end_time']);
		$total_hours = $this->getLeaveHours($start_datetime, $end_datetime);

		$params['hours'] = $total_hours;
		$params['start_at'] = $start_datetime;
		$params['end_at'] = $end_datetime;

		return $params;
	}
}