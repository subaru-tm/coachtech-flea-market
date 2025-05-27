<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function create()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        return view('profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user_id = Auth::id();
        $user_profile = $request->only(['name', 'post_code', 'address', 'building']);
        User::find($user_id)->update($user_profile);

        return redirect('/');
    }
}
