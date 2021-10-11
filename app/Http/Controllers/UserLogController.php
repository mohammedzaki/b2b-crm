<?php

namespace App\Http\Controllers;

use App\Extensions\DateTime;
use App\Models\AbsentType;
use App\Models\Attendance;
use App\Models\ClientProcess;
use App\Models\Employee;
use App\Models\User;
use App\UserLog\Models\UserLog;
use Illuminate\Http\Request;
use Auth;
use Validator;

/**
 * @Controller(prefix="user-logs")
 * @Middleware({"web", "auth"})
 */
class UserLogController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @Get("/", as="userLog.index")
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user->ability('admin', 'show-user-log')) {
            return response()->view('errors.403', [], 403);
        }
        $startDate = DateTime::today()->format('Y-m-d 00:00:00');
        $endDate   = DateTime::now()->format('Y-m-d 23:59:59');

        return $this->getUserLogs($startDate, $endDate);
    }

    private function getUserLogs($startDate, $endDate, $user_id = NULL, $row_id = NULL)
    {
        $users = User::all()->mapWithKeys(function ($user) {
            return [$user->id => $user->username];
        });

        $query = UserLog::query();

        $query->when(!empty($startDate), function ($q) use ($startDate, $endDate) {
            return $q->whereBetween('created_at', [$startDate, $endDate]);
        });

        $query->when(!empty($user_id), function ($q) use ($user_id) {
            return $q->where('user_id', '=', $user_id);
        });
        $query->when(!empty($row_id), function ($q) use ($row_id) {
            return $q->where('row_id', '=', $row_id);
        });

        $query->orderBy('created_at', 'asc');

        $userLogs = $query->get();

        $logs = $userLogs->mapWithKeys(function ($item, $key) {
            return [
                $key => [
                    'user'       => $item->user->username,
                    'employee'   => $item->user->employee->name,
                    'action'     => $item->action->display_name,
                    'entity'     => $item->entity->display_name,
                    'log_data'   => $item->getLogData(),
                    'created_at' => $item->created_at
                ]
            ];
        });

        return view('user-logs.index')->with([
                                                 'users'     => $users,
                                                 'userLogs'  => $logs,
                                                 'startDate' => $startDate,
                                                 'endDate'   => $endDate,
                                                 'user_id'   => $user_id
                                             ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     * @Get("search", as="userLog.search")
     */
    public function search(Request $request)
    {
        $user = Auth::user();
        if (!$user->ability('admin', 'show-user-log')) {
            return response()->view('errors.403', [], 403);
        }
        return $this->getUserLogs($request['startDate'], $request['endDate'], $request['user_id'], $request['row_id']);
    }

}
