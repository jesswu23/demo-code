<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeaveListService;
use App\Services\CalendarService;
use App\Http\Requests\LeaveStoreRequest;
use App\Http\Requests\LeaveUpdateRequest;

class LeaveController extends Controller
{
	protected $leaveListService;
	protected $calendarService;

	public function __construct(LeaveListService $leaveListService, CalendarService $calendarService)
	{
		$this->leaveListService = $leaveListService;
		$this->calendarService = $calendarService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('leave.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  App\Http\Requests\LeaveStoreRequest $leaveStoreRequest custom request
	 * @return \Illuminate\Http\Response
	 */
	public function store(LeaveStoreRequest $leaveStoreRequest)
	{
		// Create leave
        $result = $this->leaveListService->create($leaveStoreRequest->all());

        return redirect('/leave/create')->with('success', 'Apply success.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(int $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit(int $id)
	{
		$leaveList = $this->leaveListService->get($id);

        return view('leave/edit')
                ->with('leaveList', $leaveList);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  App\Http\Requests\LeaveUpdateRequest $leaveUpdateRequest custom request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(LeaveUpdateRequest $leaveUpdateRequest, int $id)
	{
		$result = $this->leaveListService->update($id, $leaveUpdateRequest->all());

		return redirect('/leave/edit/' . $id)->with('success', 'Update success.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(int $id)
	{
		//
	}

	public function events()
	{
		return $this->leaveListService->events();

	}
}
