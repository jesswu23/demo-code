<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeaveListService;
use App\Services\CalendarService;

class LeaveController extends Controller
{
	protected $leaveListService;
	protected $calendarService;

	public function __construct( LeaveListService $leaveListService, CalendarService $calendarService ) {
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
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		// Create leave;
        $result = $this->leaveListService->create($request->all());

        if( $result['status'] == 'error' ) {
        	return redirect('leave/create')->withError( $result['message'] );
        } else {
        	return redirect('leave/create')->withSuccess( $result['message'] );
        }
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$model = $result = $this->leaveListService->get( $id );

        return view('leave/edit')
                ->with('model', $model);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$this->leaveListService->update( $id, $request->all() );

        return redirect('/leave/edit/' . $id)->withSuccess('Update leave data success.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	public function events()
	{
		return $this->leaveListService->events();

	}
}
