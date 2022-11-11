<?php

namespace App\Repositories;

use App\Models\LeaveList;

class LeaveListRepository
{
    public function create(array $params)
    {
        return LeaveList::create($params);
    }

    public function lists()
	{
		$models = LeaveList::get();

		return $models;
	}
}
