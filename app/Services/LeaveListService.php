<?php

namespace App\Services;

use App\Repositories\LeaveListRepository;

Class LeaveListService
{
    protected $leaveListRepository;

    public function __construct( LeaveListRepository $leaveListRepository ) {
        $this->leaveListRepository = $leaveListRepository;
    }

    public function create(array $params)
    {

        $start_at = strtotime($params['start_date']);
        $end_at = strtotime($params['end_date']);

        $hours = ($end_at - $start_at) / 60 / 60;

        if($hours < 4) {
            return ['status' => 'error', 'message' => 'Take at least four hours of leave.'];
        }

        return $this->leaveListRepository->create([
            'user_id' => $params['user_id'],
            'start_at' => $params['start_date'],
            'end_at' => $params['end_date'],
            'hours' => $hours,
            'type' => $params['type'],
            'reason' => $params['reason']
        ]);
    }
}
