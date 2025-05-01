<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ProductScrapeController;
use App\Models\Product;
use App\Http\Controllers\ChatSupportController;



// Default home page

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard'); // Redirect logged-in users to dashboard
    }
    return view('welcome'); // Show homepage only to guests
})->name('home');


// Registration Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Dashboard - requires authentication & email verification
Route::get('/dashboard', function () {
    $products = Product::latest()->take(12)->get();
    return view('dashboard', compact('products'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Avatar Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/createavatar', [AvatarController::class, 'create'])->name('avatar.create');
    Route::post('/createavatar', [AvatarController::class, 'store'])->name('avatar.store');
    Route::put('/createavatar/{id}', [AvatarController::class, 'update'])->name('avatar.update');
});


// Product
Route::post('/scrape-product', [ProductScrapeController::class, 'scrape'])
    ->middleware(['auth', 'verified'])
    ->name('scrape.product');

Route::get('/product/{product}', [ProductScrapeController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('product.show');

// Product select
Route::post('/products/select-images', [ProductScrapeController::class, 'selectImages'])->name('products.select-images');

// Try-on (controller-based with middleware)
Route::get('/tryon', [AvatarController::class, 'tryon'])
    ->middleware(['auth', 'verified'])
    ->name('tryon');

// About Us
Route::get('/aboutus', function () {
    return view('aboutus');
})->middleware(['auth', 'verified'])->name('aboutus');

// Avatar Success Page
Route::get('/avatar/success', function () {
    return view('avatar.success');
})->middleware(['auth', 'verified'])->name('avatar.success');

Route::post('/avatar-upload', [AvatarController::class, 'upload'])->name('avatar.upload');

// For normal free chat (OpenAI)
Route::post('/chat/support', [ChatSupportController::class, 'chat'])->name('chat.support');

// For free-text based outfit suggestion
Route::post('/chat/suggest-outfit', [ChatSupportController::class, 'suggestOutfit'])->name('chat.suggestOutfit');

// For form submission outfit suggestion
Route::post('/chat/suggest-outfit-form', [ChatSupportController::class, 'suggestOutfitForm'])->name('chat.suggestOutfitForm');

Route::get('/chat', function () {
    return view('chat');
});

require __DIR__.'/auth.php';

