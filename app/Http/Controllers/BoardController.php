<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use App\Models\Task;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * Class BoardController
 *
 * @package App\Http\Controllers
 */
class BoardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function boards()
    {
        /** @var User $user */
        $user = Auth::user();

        $boards = Board::with(['user', 'boardUsers']);
        
        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                //Suntem in tabele de boards in continuare
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        //Suntem in tabela de board_users
                        $query->where('user_id', $user->id);
                    });
            });
        }

        $boards = $boards->paginate(10);

        return view(
            'boards.index',
            [
                'boards' => $boards
            ]
        );
    }

   
    public function updateBoard(Request $request, $id): JsonResponse
    {
        $board = Board::find($id);

        $error = '';
        $success = '';

        if ($board) {
            $name = $request->get('name');
            
                $board->name = $name;
                $board->save();
                $board->refresh();

                $success = 'Board saved';
            
        } else {
            $error = 'Board not found!';
        }

        return response()->json(['error' => $error, 'success' => $success, 'board' => $board]);
    }
    
        public function deleteBoard(Request $request, $id): JsonResponse
        {
            $board = Board::find($id);
    
            $error = '';
            $success = '';
    
            if ($board) {
                $board->delete();
    
                $success = 'Board deleted';
            } else {
                $error = 'Board not found!';
            }
    
            return response()->json(['error' => $error, 'success' => $success]);
        }    
   

    /**
     * @param $id
     *
     * @return Application|Factory|View|RedirectResponse
     */
    public function board($id)
    {
        /** @var User $user */
        $user = Auth::user();

        $boards = Board::query();
        

        if ($user->role === User::ROLE_USER) {
            $boards = $boards->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereHas('boardUsers', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                    });
            });
        }


        $board = clone $boards;
        $board = $board->where('id', $id)->first();
        // dd($board->id); die();
        $boards = $boards->select('id', 'name')->get();

        if (!$board) {
            return redirect()->route('boards.all');
        }

        // $tasks = Task::query();

        // $tasks = $tasks->where(function ($query) use ($board) {
        //     $query->where('board_id',$board->id);
        // });
        
        //dd($tasks); die();
        //$tasks = DB::table('tasks')->with('user');
        //$tasks = Task::with('user')->where('board_id',$id);
        
        $tasks = DB::select('select * from tasks where board_id = ?', [$board->id]);
        
        

        return view(
            'boards.view',
            [
                'board' => $board,
                'boards' => $boards,
                'tasks' => $tasks,
            ]
        );
    }
}
