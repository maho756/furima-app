<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController; 
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\AddressController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index'])
// ->middleware(['verified', 'profile.complete'])
->name('home');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

Route::middleware(['auth', 'verified'])->group(function(){

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    Route::middleware(['verified', 'profile.complete'])->group(function(){
        Route::post('/item/{item_id}/comments', [CommentController::class, 'store'])->name('comments.store');

        Route::post('/item/{item_id}/like', [LikeController::class, 'store']);


        Route::delete('/item/{item_id}/like', [LikeController::class, 'destroy']);

        Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');

        Route::post('/purchase/checkout', [PurchaseController::class, 'checkout'])->name('purchase.checkout');
        Route::get('/purchase/success', [PurchaseController::class, 'success'])->name('purchase.success');
        Route::get('/purchase/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');

        Route::get('/purchase/{item_id}', [PurchaseController::class, 'index'])->name('purchase.index');

        Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

        Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('purchase.address.edit');

        Route::post('/purchase/address/{item_id}', [AddressController::class,'update'])->name('purchase.address.update');

        Route::get('/sell', [ItemController::class, 'create'])->name('items.create');

        Route::post('/items', [ItemController::class, 'store'])->name('items.store');


    });
});

