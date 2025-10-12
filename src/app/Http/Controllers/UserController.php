<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $listedItems = $user->items()->get();// 出品一覧
        $purchasedItems = $user->orders()->with('item')->get();// 購入一覧（単品）

        return view('mypage.show', compact('user', 'listedItems', 'purchasedItems'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('mypage.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')
                ->store('profile_images', 'public');
        }

        $user->update($validated);

        return redirect()->route('mypage.show')
                        ->with('status', 'プロフィールを更新した');
    }
}