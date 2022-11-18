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
		$formatDate = $this->formatDate($date, "Ymd"); // The database format is YYYYMMDD

		// when the date does not exist, it will need to be written
		$params['date'] = $formatDate;
		$params['week'] = $this->getWeek($date);

		$calendar = $this->calendarRepository->updateByDate($formatDate, $params);

		return $calendar;
	}

	protected function formatDate(string $date, string $format = 'Y-m-d H:i:s')
	{
		$date_object = Carbon::create($date);
		$date = $date_object->format('Ymd');

		return $date;
	}

	protected function getWeek(string $date)
	{
		$date_object = Carbon::create($date);
		$week = $date_object->dayOfWeek; // $week = 0 ~ 6, 0 means sunday

		switch ($week) {
			case '0': $weekName = '日'; break;
			case '1': $weekName = '一'; break;
			case '2': $weekName = '二'; break;
			case '3': $weekName = '三'; break;
			case '4': $weekName = '四'; break;
			case '5': $weekName = '五'; break;
			case '6': $weekName = '六'; break;
		}

		return $weekName;
	}
}
