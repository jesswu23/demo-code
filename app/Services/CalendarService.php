<?php

namespace App\Services;

use App\Repositories\CalendarRepository;

Class CalendarService
{
	protected $calendarRepository;

	public function __construct( CalendarRepository $calendarRepository ) {
		$this->calendarRepository = $calendarRepository;
	}

	public function getByDate( $date = null )
	{
		if( !$date ) {
			return false;
		}

		$date = date_create( $date );
		$date = date_format( $date, "Ymd" ); // The database format is YYYYMMDD
		$model = $this->calendarRepository->getByDate( $date );

		return $model;
	}

	public function updateByDate( $date = null, array $params)
	{
		if( !$date ) {
			return false;
		}

		$date = date_create( $date );
		$date = date_format( $date, "Ymd" ); // The database format is YYYYMMDD
		$model = $this->calendarRepository->updateByDate( $date, $params );

		return $model;
	}
}
