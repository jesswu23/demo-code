<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\CalendarsImport;

class ImportController extends Controller
{
	protected $calendarsImport;

	public function __construct(CalendarsImport $calendarsImport)
	{
		$this->calendarsImport = $calendarsImport;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('import.index');
	}

	public function importFile(Request $request)
	{
		if($request->importFile->isValid()) {
			Excel::import($this->calendarsImport, $request->importFile);

			return redirect("import")->with('success', 'Import success.');
		} else {
			return redirect("import")->with('error', 'Import success.');
		}
	}
}
