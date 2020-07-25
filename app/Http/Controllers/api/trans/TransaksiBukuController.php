<?php

namespace App\Http\Controllers\api\trans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\master\Member;
use App\Models\master\Book;
use App\Models\trans\Books_out;
class TransaksiBukuController extends Controller
{

    public function borrowedDetail($id_book){

        $borrowed = \DB::table('books_outs as bo')
                        ->join('books as b', 'bo.id_book', 'b.id')
                        ->join('members as m', 'm.id', 'bo.id_member')
                        ->select(\DB::raw("bo.id, b.title, b.author, b.published, b.isbn, bo.id_member, m.fullname, bo.borrow_date, bo.return_date, bo.actual_return_date, bo.fine, bo.status"))
                        ->where(['bo.id_book' => $id_book, 'bo.status' => 0])
                        ->first();

        return response()->json($borrowed);

    }

    public function borrowedReport(){
        $borrowed = \DB::table('books_outs as bo')
                        ->join('books as b', 'bo.id_book', 'b.id')
                        ->join('members as m', 'm.id', 'bo.id_member')
                        ->select(\DB::raw("bo.id, b.title, b.author, b.published, b.isbn, bo.id_member, m.fullname, bo.borrow_date, bo.return_date, bo.actual_return_date, bo.fine, bo.status"))
                        ->orderBy('bo.actual_return_date', 'asc')
                        ->get();

        return response()->json($borrowed);
    }

    public function returnBook(Request $request, $id){

        $return = Books_out::findOrFail($id);

        $request->validate([
            'actual_return_date' => 'required|date',
            'total_fine' => 'required',
        ]);

        \DB::beginTransaction();
        try {

            // dd($request->total_fine);

            $return->update([
                'actual_return_date' => Carbon::parse($request->actual_return_date)->format('Y-m-d'),
                'fine' => $request->total_fine,
                'status' => 1,
            ]);

            if ($return) {
                $book = Book::findOrFail($return->id_book);
                $book->update([
                    'is_borrowed' => 0,
                ]);
            }

        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Pengembalian buku berhasil',
        ]);


    }

    public function borrowedBooks(){

        $borrowed = \DB::table('books_outs as bo')
                        ->join('books as b', 'bo.id_book', 'b.id')
                        ->join('members as m', 'm.id', 'bo.id_member')
                        ->select(\DB::raw("bo.id, b.title, b.author, b.published, b.isbn, bo.id_member, m.fullname, bo.borrow_date, bo.return_date"))
                        ->where('bo.status', 0)
                        ->orderBy('bo.return_date', 'asc')
                        ->get();

        return response()->json($borrowed);

    }

    public function borrowBook(Request $request, $id_book){

        $buku = Book::findOrFail($id_book);

        $request->validate([
            'id_member' => 'required',
            'borrow_date' => 'required|date',
            'return_date' => 'required|date',
        ]);

        \DB::beginTransaction();
        try {
            $borrow = Books_out::create([
                'id_member' => $request->id_member,
                'id_book' => $id_book,
                'borrow_date' => Carbon::parse($request->borrow_date)->format('Y-m-d'),
                'return_date' => Carbon::parse($request->return_date)->format('Y-m-d'),
                'id_user' => auth('api')->user()->id,
                'status' => 0,
            ]);

            if ($borrow) {
                $buku->update([
                    'is_borrowed' => 1,
                ]);
            }
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json($buku);


    }

    public function activeMembers(){

        $member = \DB::table('members')
                    ->select(\DB::raw("id as value, fullname as text"))
                    ->orderBy('fullname', 'asc')
                    ->get();

        return response()->json($member);

    }

    public function availableBooks(){

        $book = Book::where('is_borrowed', 0)->get();

        return response()->json($book);

    }
}
