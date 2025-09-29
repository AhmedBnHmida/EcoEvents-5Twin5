
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/RTL', function () {
    return view('RTL');
})->name('RTL')->middleware('auth');



Route::get('/tables', function () {
    return view('tables');
})->name('tables')->middleware('auth');

Route::get('/wallet', function () {
    return view('wallet');
})->name('wallet')->middleware('auth');




Route::get('/', function () {
    return view('home');
});








Route::get('/profile', function () {
    return view('account-pages.profile');
})->name('profile')->middleware('auth');

Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');

Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');

Route::get('/sign-up', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('sign-up');

Route::post('/sign-up', [RegisterController::class, 'store'])
    ->middleware('guest');

Route::get('/sign-in', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('sign-in');

Route::post('/sign-in', [LoginController::class, 'store'])
    ->middleware('guest');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest');

Route::get('/laravel-examples/user-profile', [ProfileController::class, 'index'])->name('users.profile')->middleware('auth');
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('users.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');
// Routes resources pour la gestion des entités du backoffice
Route::resource('events', App\Http\Controllers\EventController::class);
Route::resource('categories', App\Http\Controllers\CategoryController::class);

// Public routes - no conflict
Route::get('/events', [App\Http\Controllers\EventController::class, 'publicIndex'])->name('events.public');
Route::get('/events/{id}', [App\Http\Controllers\EventController::class, 'publicShow'])->name('events.public.show');

Route::get('/manage/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
Route::get('/manage/events/{id}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::resource('feedback', App\Http\Controllers\FeedbackController::class);
Route::resource('evaluations', App\Http\Controllers\EvaluationController::class);
Route::resource('ressources', App\Http\Controllers\RessourceController::class);
Route::resource('fournisseurs', App\Http\Controllers\FournisseurController::class);
Route::resource('inscriptions', App\Http\Controllers\InscriptionController::class);
Route::resource('partenaires', App\Http\Controllers\PartenaireController::class);
Route::resource('sponsoring', App\Http\Controllers\SponsoringController::class);
require __DIR__.'/auth.php';
