<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Task;
use App\Models\Board;
use App\Models\BoardUser;
use Illuminate\Support\Facades\DB;

/**
 * Class AdminController
 *
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    public function users()
    {
        $users = DB::table('users')->paginate(10);

        return view(
            'users.index',
            [
                'users' => $users
            ]
        );
    }
    

    public function edit(Request $request, $id) {
        $user = User::findOrFail($id);
        
        if (!$request->has('role')) {
            return false;
        }

        $user->role = (int)$request->get('role');

        if ($user->save()) {
            return true;
            // return redirect('/users')->with('success', 'Successfully deleted the user!');
        }
        
        return false;
        // return redirect('/users')->with('error', 'Error deleting user!');
    }

    public function delete($id) {
        $user = User::findOrFail($id);

        //Delete tasks
        $user_tasks = Task::where('assignment', $id)->delete();
        //Delete board_users
        $user_boards = BoardUser::where('user_id', $id)->delete();

        //Delete boards and things related
        $boards = Board::where('user_id', $id)->get();
        foreach($boards as $board) {
            $tasks = Task::where('board_id', $board->id)->delete();
            $tmp_boards = BoardUser::where('board_id', $board->id)->delete();
            $board->delete();
        }

        if ($user->delete()) {
            return true;
            // return redirect('/users')->with('success', 'Successfully deleted the user!');
        }
        
        return false;
        // return redirect('/users')->with('error', 'Error deleting user!');
    }
}
