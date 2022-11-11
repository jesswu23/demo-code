<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

use App\Imports\CalendarsImport;

class ImportController extends Controller
{
	protected $calendarsImport;

	public function __construct( CalendarsImport $calendarsImport ) {
		$this->calendarsImport = $calendarsImport;
	}

	public function importFile( Request $request ){

		if( $request->importFile->isValid() ) {
			Excel::import( $this->calendarsImport, $request->importFile );

			return redirect("import")->withSuccess( 'Import success');
		} else {
			return redirect("import")->withError( 'Import failure' );
		}
	}
}
