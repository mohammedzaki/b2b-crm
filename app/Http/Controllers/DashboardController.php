<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Client;
use App\Supplier;
use App\ClientProcess;

class DashboardController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->middleware('auth');
	}
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$numbers['clients_number'] = Client::count();
    	$numbers['suppliers_number'] = Supplier::count();
        $numbers['process_number'] = ClientProcess::count();
        return view('dashboard', compact('numbers'));
    }
}
