<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

// ログイン限定機能
Route::middleware('auth')->group(function () {
    Route::post('/items/{item}/like', [LikeController::class, 'toggle'])->name('like.toggle');

    // 購入関連
    Route::prefix('purchase')->group(function () {
        Route::get('/{item}', [OrderController::class, 'create'])->name('purchase.create');
        Route::post('/{item}', [OrderController::class, 'store'])->name('purchase.store');
        Route::get('/address/{item}', [OrderController::class, 'editAddress'])->name('purchase.editAddress');
        Route::put('/address/{item}', [OrderController::class, 'updateAddress'])->name('purchase.updateAddress');
    });

    Route::prefix('mypage')->group(function () {
        Route::get('/', [UserController::class, 'show'])->name('mypage.show');
        Route::get('/profile', [UserController::class, 'edit'])->name('mypage.edit');
        Route::put('/profile', [UserController::class, 'update'])->name('mypage.update');
    });

    // 出品画面表示
    Route::get('/items/create', [ItemController::class, 'create'])
        ->name('items.create');

    // 出品処理
    Route::post('/items', [ItemController::class, 'store'])
        ->name('items.store');
});