<?php

namespace App\Http\Controllers\api\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\master\Member;
class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $member = Member::orderBy('id', 'asc')->get();

        return response()->json($member);
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
            'fullname' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $member = Member::create([
                'fullname' => $request->fullname,
                'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
                'address' => $request->address,
                'is_active' => '1',
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Member berhasil ditambah',
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
        //
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
        $request->validate([
            'fullname' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
            'is_active' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            $member = Member::findOrFail($id);
            $member->update([
                'fullname' => $request->fullname,
                'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
                'address' => $request->address,
                'is_active' => $request->is_active,
            ]);
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Member berhasil diubah',
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
        $member = Member::findOrFail($id);

        \DB::beginTransaction();
        try {
            $member->delete();
        } catch (\Exception $e) {
            \DB::rollback();
            throw $e;
        }

        \DB::commit();

        return response()->json([
            'msg' => 'Member berhasil dihapus',
        ]);

    }
}
