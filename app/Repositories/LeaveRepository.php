<?php

namespace App\Repositories;

use App\Models\Leave;

class LeaveRepository
{
	public function create(array $params)
	{
		return Leave::create($params);
	}

	public function get(int $id)
	{
		$leave = Leave::find($id);

		return $leave;
	}

	public function update(int $id, array $params )
	{
		$leave = $this->get( $id );
		$leave->start_at = $params['start_at'];
		$leave->end_at = $params['end_at'];
		$leave->hours = $params['hours'];
		$leave->status = $params['status'];
		$leave->type = $params['type'];
		$leave->reason = $params['reason'];

		$leave->save();

		return $leave;
	}

	public function updateHours(int $id, int $hours)
	{
		$leave = $this->get( $id );
		$leave->hours = $hours;

		$leave->save();

		return $leave;
	}

	public function getByDate(string $date)
	{
		$leaves = Leave::where('start_at', '<=', $date)->where('end_at', '>=', $date)->get();

		return $leaves;
	}

	/**
	 * [getLeaveTypeTotalHourByUser description]
	 * @param  int         $user_id    user id
	 * @param  int         $type       leave type id
	 * @param  string      $start_date leave start date time
	 * @param  string      $end_date   leave end date time
	 * @param  int|integer $status     approval status；default 2 means pass
	 * @return collection
	 */
	public function getLeaveTypeTotalHourByUser(int $user_id, int $type, string $start_date, string $end_date, int $status = 2)
	{
		$leaves = Leave::where('user_id', '=', $user_id)
						->where('type', '=', $type)
						->where('status', '=', $status)
						->where(function ($query) use ($start_date, $end_date) {
							$query->whereBetween('start_at', [$start_date, $end_date]);
							$query->orWhereBetween('end_at', [$start_date, $end_date]);
						})
						->get();

		return $leaves;
	}

	public function lists()
	{
		$leaves = Leave::get();

		return $leaves;
	}
}
