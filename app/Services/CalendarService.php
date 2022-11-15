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
		$date = $this->formatDate($date, "Ymd"); // The database format is YYYYMMDD
		$calendar = $this->calendarRepository->getByDate($date);

		return $calendar;
	}

	public function updateByDate(string $date, array $params)
	{
		$date = $this->formatDate($date, "Ymd"); // The database format is YYYYMMDD
		$calendar = $this->calendarRepository->updateByDate($date, $params);

		return $calendar;
	}

	protected function formatDate(string $date, string $format = 'Y-m-d H:i:s')
	{
		$date = date_create($date);
		$date = date_format($date, $format);

		return $date;
	}
}
