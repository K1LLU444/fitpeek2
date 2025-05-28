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
use App\Http\Controllers\ShirtOverlayController;
use App\Http\Controllers\ChatSupportController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\TryOnController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SizeRecommendationController;

//size recommendation new
Route::post('/recommend-size/{productId}', [SizeRecommendationController::class, 'recommend'])->middleware('auth');
Route::post('/ai-recommendation', [ProductRecommendationController::class, 'aiRecommend']);
Route::post('/ai-recommendation', [\App\Http\Controllers\ProductRecommendationController::class, 'aiRecommend'])
    ->middleware('auth');


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
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

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

Route::get('/overlay/default', [ShirtOverlayController::class, 'applyDefault'])->name('overlay.apply-default');

Route::get('/product/{product}/overlay', [ShirtOverlayController::class, 'overlay'])->name('shirt.overlay');

// For normal free chat (OpenAI)
Route::post('/chat/support', [ChatSupportController::class, 'chat'])->name('chat.support');

// For free-text based outfit suggestion
Route::post('/chat/suggest-outfit', [ChatSupportController::class, 'suggestOutfit'])->name('chat.suggestOutfit');

// For form submission outfit suggestion
Route::post('/chat/suggest-outfit-form', [ChatSupportController::class, 'suggestOutfitForm'])->name('chat.suggestOutfitForm');

Route::post('/overlay/dynamic', [ShirtOverlayController::class, 'overlayDynamic'])->name('overlay.dynamic');

// Route::post('/overlay/{product}', [ShirtOverlayController::class, 'overlay'])->name('overlay.dynamic');

Route::post('/overlay-back/{product}', [ShirtOverlayController::class, 'overlayBack'])->name('overlay.back');

// Route::post('/overlay-size/{product}', [ShirtOverlayController::class, 'overlayWithSize'])->name('overlay.dynamic.size');

// Route::post('/overlay-size/{product}', [ShirtOverlayController::class, 'overlayWithSize'])->name('overlay.bySize');

// Route::post('/overlay/by-size', [ShirtOverlayController::class, 'applyBySize'])->name('overlay.bySize');

Route::post('/overlay/by-size', [ShirtOverlayController::class, 'overlayBySize'])->name('overlay.bySize');

Route::post('/product/{product}/auto-tryon', [ShirtOverlayController::class, 'autoTryOn'])->name('product.autoTryOn');

Route::get('/chat', function () {
    return view('chat');
});

Route::post('/rate-suggestion', [ChatSupportController::class, 'rateSuggestion'])->name('chat.rateSuggestion');

Route::get('/weather', [WeatherController::class, 'index'])->name('weather');
Route::post('/get-weather', [WeatherController::class, 'getWeather']);

Route::get('/weather', [TryOnController::class, 'show'])->name('weather');

// Route::get('/clean-image', [ImageCleanController::class, 'index'])->name('clean.index');
// Route::post('/clean-image', [ImageCleanController::class, 'process'])->name('clean.process');

Route::post('/fetch-recommendations', [\App\Http\Controllers\ProductRecommendationController::class, 'fetch']);


Route::post('/get-avatar-path', [AvatarController::class, 'getAvatarPath']);

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');


require __DIR__.'/auth.php';
