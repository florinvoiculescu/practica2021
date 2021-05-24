<?php

namespace App\Http\Controllers;


use App\Models\Board;
use App\Models\BoardUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    // /**
    //  * @return Application|Factory|View
    //  */
    // public function index()
    // {
    //     return view('dashboard.index');
    // }

    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $users = DB::table('users')->get();

        $boards = DB::table('boards')->get();
        $board = DB::table('board_user')->get();
        
        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->get(); });
            $board = $board->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)->get(); });
            $boards=count($boards)+count($board);
        } 
        
        //dd(count($boards)); die();
        
        $tasks = DB::table('tasks')->where('assignment','<>', NULL)->get();
        $tasksDone = DB::table('tasks')->where('status',2)->get();
        $tasksInProgress = DB::table('tasks')->where('status',1)->get();

        return view(
            'dashboard.index',
            [
                'boards' => $boards,
                'tasks' => $tasks,
                'users' => $users,
                'tasksDone' => $tasksDone,
                'tasksInProgress' => $tasksInProgress
            ]
        );
    }
}
