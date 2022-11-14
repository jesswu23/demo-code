<?php

namespace App\Repositories;

use App\Models\LeaveList;

class LeaveListRepository
{
    public function create(array $params)
    {
        return LeaveList::create($params);
    }

    public function get(int $id)
    {
    	$leaveList = LeaveList::find( $id );

    	return $leaveList;
    }

    public function update(int $id, array $params )
    {
        $leaveList = $this->get( $id );
        if( isset($params['start_date']) ){
            $leaveList->start_at = $params['start_date'];
        }

        if( isset($params['end_date']) ){
            $leaveList->end_at = $params['end_date'];
        }

        if( isset($params['hours']) ){
            $leaveList->hours = $params['hours'];
        }

        if( isset($params['status']) ){
            $leaveList->status = $params['status'];
        }

        if( isset($params['type']) ){
            $leaveList->type = $params['type'];
        }

        if( isset($params['reason']) ){
            $leaveList->reason = $params['reason'];
        }

        $leaveList->save();

        return $leaveList;
    }

    public function getByDate(string $date)
    {
        $leaveLists = LeaveList::where('start_at', '<=', $date)->where('end_at', '>=', $date)->get();

        return $leaveLists;
    }

    public function lists()
	{
		$leaveLists = LeaveList::get();

		return $leaveLists;
	}
}
