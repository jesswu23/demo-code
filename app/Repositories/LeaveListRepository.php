<?php

namespace App\Repositories;

use App\Models\LeaveList;

class LeaveListRepository
{
    public function create(array $params)
    {
        return LeaveList::create($params);
    }

    public function get( $id = null )
    {
    	$model = LeaveList::find( $id );

    	return $model;
    }

    public function update( $id = null, array $params )
    {
        $model = $this->get( $id );
        $model->start_at = $params['start_date'];
        $model->end_at = $params['end_date'];
        $model->hours = $params['hours'];
        $model->status = $params['status'];
        $model->type = $params['type'];
        $model->reason = $params['reason'];
        $model->save();

        return $model;
    }

    public function lists()
	{
		$models = LeaveList::get();

		return $models;
	}
}
