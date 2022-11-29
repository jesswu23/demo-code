<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Repositories\LeaveRepository;
use App\Repositories\CalendarRepository;
use App\Enums\LeaveType;
use App\Enums\LeaveStatus;
use App\Enums\LeavePeriod;

Class LeaveService
{
	protected $leaveRepository;
	protected $calendarRepository;

	private const LEAVE_TYPE_MAPING = [
		1 => [
			'name'		=> '病假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 30
		],
		2 => [
			'name'		=> '事假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 14
		],
		3 => [
			'name'		=> '生理假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 12
		],
		4 => [
			'name'		=> '喪假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 0
		],
		5 => [
			'name'		=> '公傷病假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 0
		],
		6 => [
			'name'		=> '產假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 0
		],
		7 => [
			'name'		=> '安胎休養假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 30
		],
		8 => [
			'name'		=> '陪產假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 7
		],
		9 => [
			'name'		=> '產檢假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 7
		],
		10 => [
			'name'		=> '家庭照顧假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 7
		],
		11 => [
			'name'		=> '公假',
			'start_date'=> '01-01',
			'end_date'	=> '12-31',
			'limit_day'	=> 0
		],
		12 => [
			'name'		=> '特別休假',
			'start_date'=> '04-01',
			'end_date'	=> '03-31',
			'limit_day'	=> 12
		]
	];

	public function __construct(LeaveRepository $leaveRepository, CalendarRepository $calendarRepository)
	{
		$this->leaveRepository = $leaveRepository;
		$this->calendarRepository = $calendarRepository;
	}

	public function create(array $params)
	{
		$params = $this->formatParams($params);
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
		$result = $this->checkLeaveHour($params, $params['status']);
		if(!$result) {
			return $result;
		}

		$leave = $this->leaveRepository->update($id, $params);

		return $leave;
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
	 * Get leave type infomation
	 * @return array
	 */
	public function getLeaveTypes()
	{
		return self::LEAVE_TYPE_MAPING;
	}

	/**
	 * check leave hours
	 * @param  array       $params
	 * @param  int|integer $status leave approval status, default 1
	 * @return bool
	 */
	protected function checkLeaveHour(array $params, int $status = LeaveStatus::APPLIED)
	{
		// if status are reject, leave hours are not judged
		if($status === LeaveStatus::REJECT) {
			return true;
		}

		// get leave type
		$learveTypes = $this->getLeaveTypes();
		$leaveTypeInfo = $learveTypes[$params['type']];
		$limitDays = $leaveTypeInfo['limit_day'];

		// get leave limit days and converted to hour
		$limitHours = $limitDays * 8;

		// get apply leave hour by year
		$userYearLeaveHour = $this->getLeaveHours($params);

		foreach ($userYearLeaveHour as $year => $applyHour) {
			if($applyHour > $limitHours && $limitHours !== 0) {
				return false;
			}

			if(!isset($userYearLeaveHour[$year])) {
				$userYearLeaveHour[$year] = 0; // default passed leave hours are 0
			}

			$startDate = $year . '-' . $leaveTypeInfo['start_date'] ;
			$endDate = $year . '-' . $leaveTypeInfo['end_date'] ;

			// get the number of leave hours passed by the user
			$userLeaveHours = $this->leaveRepository->getLeaveTypeTotalHourByUser($params['user_id'], $params['type'], $startDate, $endDate);

			if($userLeaveHours->isNotEmpty()) {
				foreach ($userLeaveHours as $key => $userLeaveHour) {
					// Get Applied Leave Hours
					$params['start_at'] = $userLeaveHour['start_at'];
					$params['end_at'] = $userLeaveHour['end_at'];
					$getLeaveHours = $this->getLeaveHours($params);

					if($getLeaveHours) {
						$userYearLeaveHour[$year] += $getLeaveHours[$year];
					}

					// check it's over the limit hour and limit hour isn't 0
					if($userYearLeaveHour[$year] > $limitHours && $limitHours !== 0) {
						return false;
					}
				}
			}
		}

		return true;
	}

	/**
	 * Calculate leave hours
	 * @param  array  $params  parameter
	 * @return array           leave hours
	 */
	protected function getLeaveHours(array $params)
	{
		$type = $params['type'];
		$learveTypes = $this->getLeaveTypes();
		$leaveTypeInfo = $learveTypes[$type];

		$startDatetimeObject = Carbon::create($params['start_at']);
		$endDatetimeObject = Carbon::create($params['end_at']);

		$startDate = $startDatetimeObject->format('Ymd'); // The database format is YYYYMMDD
		$startYear = $startDatetimeObject->year;
		$startMonth = $startDatetimeObject->month;
		$startHour = $startDatetimeObject->hour;
		$endDate = $endDatetimeObject->format('Ymd'); // The database format is YYYYMMDD
		$endYear = $endDatetimeObject->year;
		$endMonth = $endDatetimeObject->month;
		$endHour = $endDatetimeObject->hour;

		$calculationStartDateObject = Carbon::create(($startYear-1) . '-' . $leaveTypeInfo['start_date']);
		$calculationStartDateMonth = $calculationStartDateObject->month;
		$calculationEndDateObject = Carbon::create($endYear . '-' . $leaveTypeInfo['end_date']);
		$calculationEndDateMonth = $calculationEndDateObject->month;

		$leaveYear[] = $startYear;
		if($startYear !== $endYear) {
			$leaveYear[] = $endYear;
		}

		$workTotalHours = [];
		if($leaveYear) {
			$dateRange = [];
			foreach ($leaveYear as $year) {
				$tmp['year'] = $year;

				if($year === $startYear && $year === $endYear) {
					if($type === LeaveType::SPECIAL_LEAVE) {
						// The start month is less than the calculation start month and the end month is less than or equal to the calculation end month
						if($startMonth < $calculationStartDateMonth && $endMonth <= $calculationEndDateMonth) {
							$tmp['start_date'] = $startDate;
							$tmp['end_date'] = $endDate;

							$dateRange[] = $tmp;
						} else if($startMonth < $calculationStartDateMonth && $endMonth > $calculationEndDateMonth) {
							// The start month is less than the calculation start month
							$tmp['start_date'] = $startDate;
							$tmp['end_date'] = $year . str_replace('-', '', $leaveTypeInfo['end_date']);

							$dateRange[] = $tmp;

							// End month is greater than calculation end month
							$tmp['year'] = ($year+1);
							$tmp['start_date'] = $year . str_replace('-', '', $leaveTypeInfo['start_date']);
							$tmp['end_date'] = $endDate;

							$dateRange[] = $tmp;

						} else if($startMonth >= $calculationStartDateMonth) { // The start month is greater than or equal to the calculation start month
							$tmp['year'] = ($year+1);
							$tmp['start_date'] = $startDate;
							$tmp['end_date'] = $endDate;

							$dateRange[] = $tmp;
						}
					} else {
						$tmp['start_date'] = $startDate;
						$tmp['end_date'] = $endDate;

						$dateRange[] = $tmp;
					}
				} else {
					if($type === LeaveType::SPECIAL_LEAVE) {
						if($startMonth >= $calculationStartDateMonth && $endMonth <= $calculationEndDateMonth) {

							$tmp['year'] = ($year !== $startYear && $year === $endYear) ? ($year-1) : $year;
							$tmp['start_date'] = $startDate;
							$tmp['end_date'] = $endDate;

							$dateRange[] = $tmp;
						}
					} else {
						if($year === $startYear && $year !== $endYear) {
							$tmp['start_date'] = $startDatetimeObject->format('Ymd');
							$tmp['end_date'] = $year . str_replace('-', '', $leaveTypeInfo['end_date']);

							$dateRange[] = $tmp;
						} else {
							$tmp['start_date'] = $year . str_replace('-', '', $leaveTypeInfo['start_date']);
							$tmp['end_date'] = $endDatetimeObject->format('Ymd');

							$dateRange[] = $tmp;
						}
					}
				}
			}

			foreach ($dateRange as $key => $date) {
				$startDate = $date['start_date'];
				$endDate = $date['end_date'];
				$year = $date['year'];

				// get calendar list
				$calendarList = $this->calendarRepository->getCalendarListByDateRange($startDate, $endDate);

				$workdays = 0;
				foreach ($calendarList as $key => $calendar) {

					$day = 1; // default all day

					// confirm the start date and end date of leave days
					if(($calendar->date == $startDate) && $startHour === LeavePeriod::AFTERNOON_START_HOUR ) {
						$day = 0.5;
					} else if(($calendar->date == $endDate) && $endHour === LeavePeriod::MORNING_END_HOUR ) {
						$day = 0.5;
					}

					// check if it's a workdays
					if($calendar->is_holiday === 0) {
						$workdays += $day;
					}
				}

				$workTotalHours[$year] = $workdays * 8;
			}
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

		$params['start_at']	= $combineDateTime['start_datetime'];
		$params['end_at']	= $combineDateTime['end_datetime'];
		$params['user_id']	= Auth::user()->id;

		$totalHours = $this->getLeaveHours($params);
		$params['hours']	= array_sum($totalHours);

		return $params;
	}
}