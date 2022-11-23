<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\LeaveRepository;
use App\Repositories\CalendarRepository;
use Carbon\Carbon;
use App\Enums\LeaveType;
use Config;

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
		if(!$params) {
			return $params;
		}

		$result = $this->checkLeaveHour($params);
		if(!$result) {
			return $result;
		}

		$leave = $this->leaveRepository->create($params);

		return $leave;
	}

	public function update(int $id, array $params)
	{
		$params = $this->formatParams($params);
		if(!$params) {
			return $params;
		}

		$result = $this->checkLeaveHour($params, $params['status']);
		if(!$result) {
			return $result;
		}

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

		// get leave type
		$learveTypes = $this->getLeaveTypes();

		// Get data after this date
		$leaves = $this->leaveRepository->getByDate($date);
		foreach ($leaves as $key => $leave) {
			$leaveTypeInfo = (isset($leave['type'])) ? ($learveTypes[$leave['type']]) : '';
			$totalHours = $this->getLeaveHours($leave['start_at'], $leave['end_at'], $leaveTypeInfo);

			if($totalHours) {
				$this->leaveRepository->updateHours($leave->id, array_sum($totalHours));
			}
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
	 * Get leave type infomation from config
	 * @return array
	 */
	public function getLeaveTypes()
	{
		return Config::get('leave.leave_types');
	}

	/**
	 * check leave hours
	 * @param  array       $params
	 * @param  int|integer $status leave approval status, default 1 means already applied
	 * @return bool
	 */
	protected function checkLeaveHour(array $params, int $status = 1)
	{
		// if status are reject, leave hours are not judged
		if($status === 3) {
			return true;
		}

		// get leave type
		$learveTypes = $this->getLeaveTypes();
		$leaveTypeInfo = (isset($params['type'])) ? ($learveTypes[$params['type']]) : '';
		$identify = ($leaveTypeInfo) ? $leaveTypeInfo['identify'] : '';
		if(!$identify) {
			return false;
		}

		// get leave type limit infomation
		$limitDayInfo = LeaveType::getLimitDayInfo($identify);
		if($limitDayInfo['status'] === 'error') {
			return false;
		}

		// get leave limit days and converted to hour
		$limitHours = $limitDayInfo['limit_day'] * 8;

		$startDatetimeObject = Carbon::create($params['start_at']);
		$endDatetimeObject = Carbon::create($params['end_at']);

		$startYear = $startDatetimeObject->year;
		$endYear = $endDatetimeObject->year;

		$userYearLeaveHour = $this->getLeaveHours($params['start_at'], $params['end_at'], $leaveTypeInfo);
		foreach ($userYearLeaveHour as $year => $applyHour) {
			if(!isset($userYearLeaveHour[$year])) {
				$userYearLeaveHour[$year] = 0; // default passed leave hours are 0
			}

			$startDate = $year . '-' . $leaveTypeInfo['start_date'] ;
			$endDate = $year . '-' . $leaveTypeInfo['end_date'] ;

			// get the number of leave hours passed by the user
			$userLeaveHours = $this->leaveRepository->getLeaveTypeTotalHourByUser($params['user_id'], $params['type'], $startDate, $endDate);

			if($userLeaveHours) {
				foreach ($userLeaveHours as $key => $userLeaveHour) {
					// Get Applied Leave Hours
					$getLeaveHours = $this->getLeaveHours($userLeaveHour['start_at'], $userLeaveHour['end_at'], $leaveTypeInfo);

					if($getLeaveHours) {
						$userYearLeaveHour[$year] += $getLeaveHours[$year];
					}

					// check it's over the limit hour and limit hour isn't 0
					if($userYearLeaveHour[$year] > $limitHours && $limitHours !== 0) {
						return false;

						break;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Calculate leave hours
	 * @param  string $startDatetime leave start datetime
	 * @param  string $endDatetime   leave end datetime
	 * @param  array  $leaveTypeInfo leave type info
	 * @return array             leave hours
	 */
	protected function getLeaveHours(string $startDatetime, string $endDatetime, array $leaveTypeInfo)
	{
		if(!$leaveTypeInfo) {
			return [];
		}

		$startDatetimeObject = Carbon::create($startDatetime);
		$endDatetimeObject = Carbon::create($endDatetime);

		$startDate = $startDatetimeObject->format('Ymd'); // The database format is YYYYMMDD
		$startYear = $startDatetimeObject->year;
		$startHour = $startDatetimeObject->hour;
		$endDate = $endDatetimeObject->format('Ymd'); // The database format is YYYYMMDD
		$endYear = $endDatetimeObject->year;
		$endHour = $endDatetimeObject->hour;

		$leaveYear[] = $startYear;
		if($startYear !== $endYear) {
			$leaveYear[] = $endYear;
		}

		$yearLeaveHour = [];
		foreach ($leaveYear as $year) {
			if($year === $startYear && $year !== $endYear) {
				$startDate = $startDatetimeObject->format('Ymd');
				$endDate = $year . str_replace('-', '', $leaveTypeInfo['end_date']);
			}

			if($year !== $startYear && $year === $endYear) {
				$startDate = $year . str_replace('-', '', $leaveTypeInfo['start_date']);
				$endDate = $endDatetimeObject->format('Ymd');
			}

			// get calendar list
			$calendarList = $this->calendarRepository->getCalendarListByDateRange($startDate, $endDate);

			$workdays = 0;
			foreach ($calendarList as $key => $calendar) {

				$day = 1; // default all day

				// confirm the start date and end date of leave days
				if(($calendar->date == $startDate) && $startHour === 14 ) {
					$day = 0.5;
				} else if(($calendar->date == $endDate) && $endHour === 13 ) {
					$day = 0.5;
				}

				// check if it's a workdays
				if($calendar->is_holiday === 0) {
					$workdays += $day;
				}
			}

			$workTotalHours[$year] = $workdays * 8;
		}

		return $workTotalHours;
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

		$learveTypes = $this->getLeaveTypes();
		$leaveTypeInfo = (isset($params['type'])) ? ($learveTypes[$params['type']]) : '';

		$totalHours = $this->getLeaveHours($startDatetime, $endDatetime, $leaveTypeInfo);
		if(!$totalHours) {
			return false;
		}

		$params['hours']	= array_sum($totalHours);
		$params['start_at']	= $startDatetime;
		$params['end_at']	= $endDatetime;
		$params['user_id']	= Auth::user()->id;

		return $params;
	}
}