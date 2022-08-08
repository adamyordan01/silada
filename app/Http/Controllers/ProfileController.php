<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {
        $user = User::where('id', auth()->id())->with(['position'])->first();
        return view('profiles.index', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $path = 'profiles/';
        $file = $request->file('photo');
        // $newName = 'IMG_' . date('Ymd') . uniqid() .'.' . $file->getClientOriginalExtension();
        $newName = 'UIMG'.date('Ymd').uniqid().'.jpg';

        $upload = $file->move(public_path($path), $newName);

        if (!$upload) {
            return response()->json([
                'status' => 0,
                'msg' => 'Error uploading file'
            ]);
        } else {
            $oldPhoto = User::find(Auth::id())->getAttributes()['photo'];

            if ($oldPhoto != '') {
                if (File::exists(public_path($path . $oldPhoto))) {
                    File::delete(public_path($path . $oldPhoto));
                }
            }

            $update = User::find(Auth::id())->update([
                'photo' => $newName
            ]);

            if (!$upload) {
                return response()->json([
                    'status' => 0,
                    'msg' => 'Something went wrong when updating photo'
                ]);
            } else {
                return response()->json([
                    'status' => 1,
                    'msg' => 'Successfully update photo'
                ]);
            }
        }
    }
}
