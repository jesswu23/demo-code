<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\LeaveListService;

class LeaveController extends Controller
{
    protected $leaveListService;

    public function __construct( LeaveListService $leaveListService ) {
        $this->leaveListService = $leaveListService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // create leave;
        $result = $this->leaveListService->create($request->all());

        return response()->json($result, 201);
    }

}
