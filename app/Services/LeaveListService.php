<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\LeaveListRepository;
use App\Repositories\CalendarRepository;
use Carbon\Carbon;

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
			$params['hours'] = $this->getLeaveHours($leaveList['start_at'], $leaveList['end_at']);

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
	 * @param  string $start_datetime leave start datetime
	 * @param  string $end_datetime   leave end datetime
	 * @return int             leave hours
	 */
	protected function getLeaveHours(string $start_datetime, string $end_datetime)
	{
		$start_datetime_object = Carbon::create($start_datetime);
    	$end_datetime_object = Carbon::create($end_datetime);

		$start_date = $start_datetime_object->format('Ymd'); // The database format is YYYYMMDD
		$start_hour = $start_datetime_object->hour;
		$end_date = $end_datetime_object->format('Ymd'); // The database format is YYYYMMDD
		$end_hour = $end_datetime_object->hour;
		$diff_days = $start_datetime_object->diffInDays($end_datetime_object);

		$current_date = $start_datetime_object->timestamp;
		$last_date    = $end_datetime_object->timestamp;

		// get holiday
		$result = $this->calendarRepository->getHolidayByDateRange($start_date, $end_date);

		$i = 0;
		$total_hours = 0;
		while ($current_date <= $last_date) {
			// check if it's a holiday
			if(!$result->where('date', date('Ymd', $current_date))->all()) {
				$date_hour = 8; // default 8 hour
				if($i == 0) {
					$date_hour = ($start_hour == '14') ? 4 : 8;
				} else if($i == $diff_days){
					$date_hour = ($end_hour == '13') ? 4 : 8;
				}

				$total_hours += $date_hour;
			}

			$current_date = strtotime('+1 day', $current_date);
			++$i;
		}

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

		$params['hours']	= $total_hours;
		$params['start_at']	= $start_datetime;
		$params['end_at']	= $end_datetime;
		$params['user_id']	= Auth::user()->id;

		return $params;
	}
}