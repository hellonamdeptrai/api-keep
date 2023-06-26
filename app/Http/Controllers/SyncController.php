<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tables = ['users', 'notes', 'images', 'files', 'labels', 'note_has_label', 'note_has_user'];

        $response = [];
        foreach ($tables as $table) {
            if ($table == 'notes') {
                $tableData = DB::table('notes')->where('user_id', Auth::user()->id)->get();
                $response[$table] = $tableData;
            } else {
                $tableData = DB::table($table)->get();
                $response[$table] = $tableData;
            }
        }

        return response()->json($response);
    }

    public function storeUser(Request $request)
    {

        $userId = User::find(Auth::user()->id);

        $user = json_decode($request->input('user'), true);
        if ($request->hasFile('avatarFiles')) {
            $avatars = $request->file('avatarFiles');

            if ($userId->avatar) {
                Storage::disk('public')->delete($userId->avatar);
            }

            $destinationPath = $avatars->store('avatars', 'public');
            $userId->avatar = $destinationPath;
        }
        $userId->name = $user['name'];
        $userId->update();
//
//        User::query()->delete();
//        $request->validate([
//            'users' => 'required'
//        ]);
//
//        $users = $request->input('users');
//
//        // Lưu danh sách người dùng vào cơ sở dữ liệu Laravel
//        foreach ($users as $userData) {
//            $user = new User;
//            $user->name = $userData['name'];
//            $user->email = $userData['email'];
//            $user->password = $userData['password'];
//            $user->updated_at = $userData['updated_at'];
//            $user->save();
//
//
//        }

        return response()->json(['message' => 'Upload successful']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
