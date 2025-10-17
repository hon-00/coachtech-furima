<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $listedItems = $user->items()->get();
        $purchasedItems = $user->orders()->with('item')->get();

        return view('mypage.show', compact('user', 'listedItems', 'purchasedItems'));
    }

    public function edit(Request $request)
    {
        $user = $request->user();
        return view('mypage.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = $request->user();

        $validated = $request->validated();

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')
                ->store('profile_images', 'public');
        }

        $user->update($validated);

        return redirect()->route('mypage.show')
                            ->with('status', 'プロフィールを更新しました。');
    }
}