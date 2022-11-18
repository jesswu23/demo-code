<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\LeaveRepository;
use App\Repositories\CalendarRepository;
use Carbon\Carbon;

Class LeaveService
{
	protected $leaveRepository;
	protected $calendarRepository;

	public function __construct(LeaveRepository $leaveRepository, CalendarRepository $calendarRepository)
	{
		$this->leaveRepository = $leaveRepository;
		$this->calendarRepository = $calendarRepository;
	}

	public function create(array $params)
	{
		$params = $this->formatParams($params);
		$leave = $this->leaveRepository->create($params);

		return $leave;
	}

	public function update(int $id, array $params)
	{
		$params = $this->formatParams($params);
		$leave = $this->leaveRepository->update($id, $params);

		return $leave;
	}

	/**
	 * update leave hour by date
	 * @param  string $date
	 * @return boolean
	 */
	public function updateByDate(string $date)
	{
		$date_object = Carbon::create($date);
		$date = $date_object->format('Y-m-d');

		// Get data after this date
		$leaves = $this->leaveRepository->getByDate($date);
		foreach ($leaves as $key => $leave) {
			$params['hours'] = $this->getLeaveHours($leave['start_at'], $leave['end_at']);

			$this->leaveRepository->update($leave->id, $params);
		}

		return true;
	}

	public function get(int $id)
	{
		$leave = $this->leaveRepository->get($id);

		$start_array = explode(' ', $leave['start_at']);
		$leave->start_date = (isset($start_array[0])) ? $start_array[0] : '';
		$leave->start_time = (isset($start_array[1])) ? date_format(date_create($start_array[1]), 'H:i') : '';

		$end_array = explode(' ', $leave['end_at']);
		$leave->end_date = (isset($end_array[0])) ? $end_array[0] : '';
		$leave->end_time = (isset($end_array[1])) ? date_format(date_create($end_array[1]), 'H:i') : '';

		return $leave;
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
		$leaves = $this->leaveRepository->lists();
		foreach ($leaves as $key => $leave) {
			$tmp['title'] = $leave->user->name;
			$tmp['start'] = $leave->start_at;
			$tmp['end'] = $leave->end_at;
			$tmp['url'] = url('leave/edit/' . $leave->id);
			$tmp['color'] = '#0d6efd';

			$events[] = $tmp;
		}

		return response()->json($events, 200);
	}

	/**
	 * Calculate leave hours
	 * @param  string $startDatetime leave start datetime
	 * @param  string $endDatetime   leave end datetime
	 * @return int             leave hours
	 */
	protected function getLeaveHours(string $startDatetime, string $endDatetime)
	{
		$startDatetimeObject = Carbon::create($startDatetime);
    	$endDatetimeObject = Carbon::create($endDatetime);

		$startDate = $startDatetimeObject->format('Ymd'); // The database format is YYYYMMDD
		$startHour = $startDatetimeObject->hour;
		$endDate = $endDatetimeObject->format('Ymd'); // The database format is YYYYMMDD
		$endHour = $endDatetimeObject->hour;
		$diffDays = $startDatetimeObject->diffInDays($endDatetimeObject);

		$currentDate = $startDatetimeObject->timestamp;
		$lastDate    = $endDatetimeObject->timestamp;

		// get holiday
		$holidayList = $this->calendarRepository->getHolidayByDateRange($startDate, $endDate);

		$i = 0;
		$total_hours = 0;
		while ($currentDate <= $lastDate) {
			// check if it's a holiday
			if(!$holidayList->where('date', date('Ymd', $currentDate))->all()) {
				$date_hour = 8; // default 8 hour

				// confirm whether to ask for leave for more than one day
				if($diffDays > 0) {
					if($i == 0) {
						// check start date hour
						$date_hour = ($startHour === 14 && $endHour === 18) ? 4 : 8;
					} else if($i == $diffDays){
						// check end date hour
						$date_hour = ($startHour === 9 && $endHour === 13) ? 4 : 8;
					}
				} else {
					if(($startHour == '9' && $endHour == '13') || $startHour == '14' && $endHour == '18') {
						$date_hour = 4;
					}
				}

				$total_hours += $date_hour;
			}

			$currentDate = strtotime('+1 day', $currentDate);
			++$i;
		}

		return $total_hours;
	}

	/**
	 * combine leave date and leave time
	 * @param  string $startDate leave start date
	 * @param  string $start_time leave start time
	 * @param  string $endDate   leave end date
	 * @param  string $end_time   leave end time
	 * @return array             leave start datetime and leave end datetime
	 */
	protected function combineDateTime(string $startDate, string $start_time, string $endDate, string $end_time)
	{
		$combine_date['start_datetime'] = $startDate . ' ' . $start_time;
		$combine_date['end_datetime'] = $endDate . ' ' . $end_time;

		return $combine_date;
	}

	/**
	 * processing parameters
	 * @param  array  $params parameters
	 * @return array          parameters array
	 */
	protected function formatParams(array $params)
	{
		$combineDateTime = $this->combineDateTime($params['start_date'], $params['start_time'], $params['end_date'], $params['end_time']);

		$startDatetime = $combineDateTime['start_datetime'];
		$endDatetime = $combineDateTime['end_datetime'];
		$total_hours = $this->getLeaveHours($startDatetime, $endDatetime);

		$params['hours']	= $total_hours;
		$params['start_at']	= $startDatetime;
		$params['end_at']	= $endDatetime;
		$params['user_id']	= Auth::user()->id;

		return $params;
	}
}