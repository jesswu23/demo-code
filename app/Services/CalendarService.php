<?php

namespace App\Services;

use App\Repositories\CalendarRepository;

Class CalendarService
{
	protected $calendarRepository;

	public function __construct(CalendarRepository $calendarRepository) {
		$this->calendarRepository = $calendarRepository;
	}

	public function getByDate(string $date)
	{
		$date = date_create($date);
		$date = date_format($date, "Ymd"); // The database format is YYYYMMDD
		$calendar = $this->calendarRepository->getByDate($date);

		return $calendar;
	}

	public function updateByDate(string $date, array $params)
	{
		$date = date_create($date);
		$date = date_format($date, "Ymd"); // The database format is YYYYMMDD
		$calendar = $this->calendarRepository->updateByDate($date, $params);

		return $calendar;
	}
}
