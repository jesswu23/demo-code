<?php

namespace App\Imports;

use App\Models\Calendar;
use Maatwebsite\Excel\Concerns\ToModel;

class CalendarsImport implements ToModel
{
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
