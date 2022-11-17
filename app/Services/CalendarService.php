<?php

namespace App\Services;

use App\Repositories\CalendarRepository;
use Carbon\Carbon;

Class CalendarService
{
	protected $calendarRepository;

	public function __construct(CalendarRepository $calendarRepository)
	{
		$this->calendarRepository = $calendarRepository;
	}

	public function getByDate(string $date)
	{
		$date = $this->formatDate($date, "Ymd"); // The database format is YYYYMMDD
		$calendar = $this->calendarRepository->getByDate($date);

		return $calendar;
	}

	public function updateByDate(string $date, array $params)
	{
		$format_date = $this->formatDate($date, "Ymd"); // The database format is YYYYMMDD

		// when the date does not exist, it will need to be written
		$params['date'] = $format_date;
		$params['week'] = $this->getWeek($date);

		$calendar = $this->calendarRepository->updateByDate($format_date, $params);

		return $calendar;
	}

	protected function formatDate(string $date, string $format = 'Y-m-d H:i:s')
	{
		$date = date_create($date);
		$date = date_format($date, $format);

		return $date;
	}

	protected function getWeek(string $date)
	{
		$date_object = Carbon::create($date);
		$week = $date_object->dayOfWeek; // $week = 0 ~ 6, 0 means sunday

		switch ($week) {
			case '0': $week_name = '日'; break;
			case '1': $week_name = '一'; break;
			case '2': $week_name = '二'; break;
			case '3': $week_name = '三'; break;
			case '4': $week_name = '四'; break;
			case '5': $week_name = '五'; break;
			case '6': $week_name = '六'; break;
		}

		return $week_name;
	}
}
