<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    //

    public function tasks()
    {
        $tasks = DB::table('tasks')->paginate(10);
        
        return view(
            'boards.view',
            [
                'tasks' => $tasks
            ]
        );
    }
}



