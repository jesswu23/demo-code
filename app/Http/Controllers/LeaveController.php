<?php

namespace App\Http\Controllers;

use App\Services\LeaveService;
use App\Services\CalendarService;
use App\Http\Requests\LeaveStoreRequest;
use App\Http\Requests\LeaveUpdateRequest;
use App\Enums\LeaveType;

class LeaveController extends Controller
{
	protected $leaveService;
	protected $calendarService;

	public function __construct(LeaveService $leaveService, CalendarService $calendarService)
	{
		$this->leaveService = $leaveService;
		$this->calendarService = $calendarService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('leave.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$leaveTypes = LeaveType::getLeaveTypeName();

		return view('leave.create')
				->with('leaveTypes', $leaveTypes);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  App\Http\Requests\LeaveStoreRequest $leaveStoreRequest custom request
	 * @return \Illuminate\Http\Response
	 */
	public function store(LeaveStoreRequest $leaveStoreRequest)
	{
		$validated = $leaveStoreRequest->validated();
		$result = $this->leaveService->create($validated);

		if(!$result) {
			return redirect('/leave/create')->with('error', 'Leave hours exceeds the prescribed number of hours');
		} else {
			return redirect('/leave/create')->with('success', 'Apply success');
		}
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
		$leave = $this->leaveService->get($id);
		$leaveTypes = LeaveType::getLeaveTypeName();

		return view('leave/edit')
				->with('leave', $leave)
				->with('leaveTypes', $leaveTypes);
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
		$validated = $leaveUpdateRequest->validated();
		$result = $this->leaveService->update($id, $validated);

		if(!$result) {
			return redirect('/leave/edit/' . $id)->with('error', 'Leave hours exceeds the prescribed number of hours');
		} else {
			return redirect('/leave/edit/' . $id)->with('success', 'Update success.');
		}
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
		return $this->leaveService->events();
	}
}
