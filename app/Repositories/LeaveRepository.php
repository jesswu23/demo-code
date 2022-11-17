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

    public function getByDate(string $date)
    {
        $leaves = Leave::where('start_at', '<=', $date)->where('end_at', '>=', $date)->get();

        return $leaves;
    }

    public function lists()
	{
		$leaves = Leave::get();

		return $leaves;
	}
}
