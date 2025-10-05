<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;

use App\Http\Controllers\OrderController;

Route::get('/', [ItemController::class, 'index'])->name('home');

// 商品関連
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
    ->name('comment.store')
    ->middleware('auth');

/*
|------------------------------------
| プロフィール関連
|------------------------------------
|
| Route::get('/profile/edit', [ProfileController::class, 'edit'])
|     ->name('profile.edit')
|     ->middleware('auth');
|
*/
// ログイン限定機能
Route::middleware('auth')->group(function () {
    Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('like.toggle');

    // 購入関連
    Route::prefix('purchase')->group(function () {
        Route::get('/{item}', [OrderController::class, 'create'])->name('purchase.create');
        Route::post('/{item}', [OrderController::class, 'store'])->name('purchase.store');
        Route::get('/address/{item}', [OrderController::class, 'editAddress'])->name('purchase.editAddress');
        Route::post('/address/{item}', [OrderController::class, 'updateAddress'])->name('purchase.updateAddress');
    });
});