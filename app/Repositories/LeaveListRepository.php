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
        if( isset($params['start_date']) ){
            $model->start_at = $params['start_date'];
        }

        if( isset($params['end_date']) ){
            $model->end_at = $params['end_date'];
        }

        if( isset($params['hours']) ){
            $model->hours = $params['hours'];
        }

        if( isset($params['status']) ){
            $model->status = $params['status'];
        }

        if( isset($params['type']) ){
            $model->type = $params['type'];
        }

        if( isset($params['reason']) ){
            $model->reason = $params['reason'];
        }

        $model->save();

        return $model;
    }

    public function getByDate( $date = null)
    {
        $models = LeaveList::where('start_at', '<=', $date)->where('end_at', '>=', $date)->get();

        return $models;
    }

    public function lists()
	{
		$models = LeaveList::get();

		return $models;
	}
}
