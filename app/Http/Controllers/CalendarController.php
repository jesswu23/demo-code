<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CalendarService;
use App\Services\LeaveListService;

class CalendarController extends Controller
{
    protected $calendarService;

    public function __construct(CalendarService $calendarService, LeaveListService $leaveListService)
    {
        $this->calendarService = $calendarService;
        $this->leaveListService = $leaveListService;
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    public function edit(string $date)
    {
        $model = $this->calendarService->getByDate($date);

        return view('calendar/edit')
                ->with('date', $date)
                ->with('model', $model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $date)
    {
        $this->calendarService->updateByDate($date, $request->all());
        $this->leaveListService->updateByDate($date);

        return redirect('/leave')->withSuccess('Update calendar success.');
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
}
