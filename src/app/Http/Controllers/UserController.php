<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

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
        $file = $request->file('img_file');
        $originalName = $file->getClientOriginalName();
        $file->storeAs('public/', $originalName);

        $image_path = 'storage/' . $originalName;

        $user_profile = $request->only(['name', 'post_code', 'address', 'building', 'image']);
        User::find($user_id)->update([
            'name' => $request->name,
            'post_code' => $request->post_code,
            'address' => $request->address,
            'building' => $request->building,
            'image' => $image_path,
        ]);

        return redirect('/');
    }

    public function mypage($tab)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);


        if ($tab == "sell")
        {
            $items = Item::UserIdSearch($user_id)->get();
            $purchases = Purchase::all();
            $tab_return = "sell";

        } elseif ($tab == "buy")
        {
            $items = Item::all();
            $purchases = Purchase::UserIdSearch($user_id)->get();
            $tab_return = "buy";

        }

        return view('profile', compact('user', 'items', 'purchases', 'tab_return'));

    }

    public function profile()
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        return view('profile-edit', compact('user'));
    }
}
