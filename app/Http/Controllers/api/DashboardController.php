<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\master\Member;
use App\Models\master\Book;
use App\Models\trans\Books_out;
class DashboardController extends Controller
{
    public function summary(){
        $users = User::count();
        $members = Member::count();
        $books = Book::count();
        $borrowed = Books_out::count();

        return response()->json([
            'users' => $users,
            'members' => $members,
            'books' => $books,
            'borrowed' => $borrowed,
        ]);
    }
}
