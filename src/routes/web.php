<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MessageDraftController;

Route::get('/', [ItemController::class, 'index'])->name('home');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

Route::middleware('auth')->group(function () {
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
        ->name('comment.store');

    Route::post('/items/{item}/like', [LikeController::class, 'toggle'])
        ->name('like.toggle');

    Route::prefix('purchase')->group(function () {
        Route::get('/address/{transaction}', [OrderController::class, 'editAddress'])
            ->name('purchase.editAddress');
        Route::put('/address/{transaction}', [OrderController::class, 'updateAddress'])
            ->name('purchase.updateAddress');

        Route::get('/{transaction}', [OrderController::class, 'create'])
            ->name('purchase.create');
        Route::post('/{transaction}', [OrderController::class, 'store'])
            ->name('purchase.store');
    });

    Route::prefix('mypage')->group(function () {
        Route::get('/', [UserController::class, 'show'])->name('mypage.show');
        Route::get('/profile', [UserController::class, 'edit'])->name('mypage.edit');
        Route::put('/profile', [UserController::class, 'update'])->name('mypage.update');
    });

    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show');
    Route::patch('/transactions/{transaction}/complete', [TransactionController::class, 'complete'])
    ->name('transactions.complete');

    Route::post('/items/{item}/transactions', [TransactionController::class, 'store'])
        ->name('transactions.store');

    Route::prefix('transactions/{transaction}')->group(function () {
        Route::post('/messages', [MessageController::class, 'store'])
            ->name('messages.store');
        Route::post('/message-drafts', [MessageDraftController::class, 'store'])
            ->name('message-drafts.store');

        Route::patch('/messages/{message}', [MessageController::class, 'update'])
            ->name('messages.update');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])
            ->name('messages.destroy');
    });

    Route::get('/items/create', [ItemController::class, 'create'])
        ->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])
        ->name('items.store');
});