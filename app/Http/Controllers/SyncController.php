<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Note;
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

        $userId = Auth::user()->id;
        $response = [];
        foreach ($tables as $table) {
            if ($table == 'notes') {
                $tableData = Note::with('users')->whereHas('users', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })->get();

                $response[$table] = $tableData;
            } else if ($table == 'labels') {
                $tableData = DB::table('labels')->where('user_id', $userId)->get();
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

    public function storeLabel(Request $request)
    {
        $userId = Auth::user()->id;
        Label::where('user_id', $userId)->delete();
        $labels = $request->input('labels');

        foreach ($labels as $label) {
            $lb = new Label();
            $lb->title = $label['title'];
            $lb->user_id = $userId;
            $lb->save();
        }

        return response()->json(['message' => 'Upload successful']);
    }

    public function storeNote(Request $request)
    {
        Auth::user()->userNotes()->delete();
        $notes = $request->input('notes');

        foreach ($notes as $note) {
            $nt = new Note();
            $nt->index = $note['index'];
            $nt->title = $note['title'];
            $nt->content = $note['content'];
            $nt->is_check_box_or_content = $note['isCheckBoxOrContent'];
            $nt->deadline = $note['deadline'] != "" ? $note['deadline'] : null;
            $nt->color = $note['color'];
            $nt->background = $note['background'];
            $nt->archive = $note['archive'];
            $nt->user_id = $note['userId'];
            $nt->save();

            $user = User::find($note['userId']);
            $user->notes()->attach(Note::find($nt->id));

            foreach ($request->input('note_has_user') as $notehas) {
                if ($notehas['user_id'] != $note['userId']) {
                    if ($notehas['note_id'] == $note['id']) {
                        $user = User::find($notehas['user_id']);
                        $user->notes()->attach(Note::find($nt->id));
                    }
                }
            }

        }

        return response()->json(['message' => 'Upload successful']);
    }

    public function storeNoteHasUser(Request $request)
    {
        $userid = $request->input('user_id');
        $noteid = $request->input('note_id');
        $user = User::find($userid);

        $user->notes()->attach(Note::find($noteid));

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
