<?php

namespace App\Http\Controllers\api\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\master\Book;
class BukuController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $buku = Book::orderBy('id', 'asc')->get();

        return response()->json($buku);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'isbn' => 'required|unique:books',
            'published' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $buku = Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'isbn' => $request->isbn,
                'published' => $request->published,
                'is_borrowed' => 0,
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Buku berhasil ditambah',
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $buku = Book::find($id)->first();

        return response()->json($buku);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $buku = Book::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'isbn' => 'required|unique:books,isbn,'.$buku->id,
            'published' => 'required',
        ]);

        \DB::beginTransaction();
        try {

            $buku->update([
                'title' => $request->title,
                'author' => $request->author,
                'isbn' => $request->isbn,
                'published' => $request->published,
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Buku berhasil diubah',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $buku = Book::findOrFail($id);

        \DB::beginTransaction();
        try {
            $buku->delete();
        } catch (\Exception $e) {
            throw $e;
            \DB::rollback();
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Buku berhasil dihapus',
        ]);

    }
}
