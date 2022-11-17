<?php

namespace App\Imports;

use App\Models\Calendar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class CalendarsImport implements ToModel, SkipsOnError
{
	use SkipsErrors;

	/**
	* @param array $row
	*
	* @return \Illuminate\Database\Eloquent\Model|null
	*/
	public function model(array $row)
	{
		if (!is_numeric($row[0])) {
			return null;
		}

		return new Calendar([
			'date'		=> $row[0],
			'week'		=> $row[1],
			'is_holiday'=> (string)($row[2]),
			'memo'		=> $row[3]
		]);
	}
}
