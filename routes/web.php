
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FournisseurController;

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
Route::put('/laravel-examples/user-profile/update', [ProfileController::class, 'update'])->name('user-profile.update')->middleware('auth');
Route::get('/laravel-examples/users-management', [UserController::class, 'index'])->name('users-management')->middleware('auth');

// Routes resources pour la gestion des entités du backoffice
Route::resource('users', App\Http\Controllers\UsersController::class);

// Public routes - no conflict
Route::get('/events', [App\Http\Controllers\EventController::class, 'publicIndex'])->name('events.public');
Route::get('/events/{id}', [App\Http\Controllers\EventController::class, 'publicShow'])->name('events.public.show');

// ADMIN Routes - Use explicit routes instead of resource to avoid conflicts
Route::middleware('auth')->group(function () {
    // Admin Events Routes
    Route::get('/manage/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/manage/events/create', [App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/manage/events', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/manage/events/{id}', [App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    Route::get('/manage/events/{id}/edit', [App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
    Route::put('/manage/events/{id}', [App\Http\Controllers\EventController::class, 'update'])->name('events.update');
    Route::delete('/manage/events/{id}', [App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');

    // Categories Routes
    Route::get('/manage/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.index');
    Route::get('/manage/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::post('/manage/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::get('/manage/categories/{id}', [App\Http\Controllers\CategoryController::class, 'show'])->name('categories.show');
    Route::get('/manage/categories/{id}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/manage/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/manage/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');
});


Route::resource('feedback', App\Http\Controllers\FeedbackController::class);
Route::resource('evaluations', App\Http\Controllers\EvaluationController::class);
Route::resource('ressources', App\Http\Controllers\RessourceController::class);
Route::resource('fournisseurs', App\Http\Controllers\FournisseurController::class);
Route::resource('inscriptions', App\Http\Controllers\InscriptionController::class);
Route::resource('partenaires', App\Http\Controllers\PartenaireController::class);
Route::resource('sponsoring', App\Http\Controllers\SponsoringController::class);
















Route::get('/dashboard-Fournisseur', function () {
    return view('dashboard-Fournisseur.dashboard');
})->name('dashboard-Fournisseur');











require __DIR__.'/auth.php';
