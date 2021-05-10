<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Board;
use Illuminate\Support\Facades\DB;

class BoardsController extends Controller
{
    //

    public function boards()
    {
        $boards = DB::table('boards')->paginate(10);
        
        return view(
            'boards.index',
            [
                'boards' => $boards
            ]
        );
    }
}
