<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $purchasedItems = $user->purchases()->with('item')->get();
        $soldItems = $user->items()->get();

        return view('mypage.show', compact('user', 'purchasedItems', 'soldItems'));
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
            'profile_image' => 'nullable|image|max:1024', // 1MBまで
        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        return redirect()->route('mypage.show')->with('status', 'プロフィールを更新しました');
    }
}
