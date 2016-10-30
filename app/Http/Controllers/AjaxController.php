<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use App\Role;

class AjaxController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    public function getManagerList(Request $request) {
        /* Get admin role */
        $managers = Role::where('name', 'admin')->first();
        /* Filter admin users based on query string */
        $filtered = $managers->users->filter(function ($user) use($request) {
                    return stripos($user->name, $request->q) !== false;
                })->all();

        if ($request->ajax()) {
            return response()->json([
                        'managers' => array_values($filtered)
            ]);
        }
    }

}
