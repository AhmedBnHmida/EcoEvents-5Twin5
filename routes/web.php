
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FournisseurController;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard API endpoints
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/dashboard/stats', [App\Http\Controllers\DashboardController::class, 'getStats'])->name('api.dashboard.stats');
    Route::get('/dashboard/event-stats', [App\Http\Controllers\DashboardController::class, 'getEventStats'])->name('api.dashboard.event-stats');
});

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



Route::post('/events/generate-description', [App\Http\Controllers\EventController::class, 'generateDescription'])->name('events.generate-description');
Route::post('/events/generate-complete-event', [App\Http\Controllers\EventController::class, 'generateCompleteEvent'])->name('events.generate-complete-event');
Route::post('/events/predict-success', [App\Http\Controllers\EventController::class, 'predictEventSuccess'])->name('events.predict-success');



Route::get('/signin', function () {
    return view('account-pages.signin');
})->name('signin');

Route::get('/signup', function () {
    return view('account-pages.signup');
})->name('signup')->middleware('guest');
/*
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
*/
// Routes resources pour la gestion des entités du backoffice
Route::resource('users', App\Http\Controllers\UsersController::class);

// Public routes - no conflict
Route::get('/events', [App\Http\Controllers\EventController::class, 'publicIndex'])->name('events.public');
Route::get('/events/{id}', [App\Http\Controllers\EventController::class, 'publicShow'])->name('events.public.show');

// Participant Registration Routes (create can be accessed by guests, will redirect to login)
Route::get('/registrations/create', [App\Http\Controllers\RegistrationController::class, 'create'])->name('registrations.create');

Route::middleware('auth')->group(function () {
    Route::post('/registrations', [App\Http\Controllers\RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/{registration}', [App\Http\Controllers\RegistrationController::class, 'show'])->name('registrations.show');
    Route::delete('/registrations/{registration}', [App\Http\Controllers\RegistrationController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/my-registrations', [App\Http\Controllers\RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    
    // Payment Routes
    Route::get('/payment/checkout/{registration}', [App\Http\Controllers\PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{registration}', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{registration}', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

    // Feedback Routes (Participants)
    Route::get('/feedback/create', [App\Http\Controllers\FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}/edit', [App\Http\Controllers\FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [App\Http\Controllers\FeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::get('/my-feedbacks', [App\Http\Controllers\FeedbackController::class, 'myFeedbacks'])->name('feedback.my');

});

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

    // Admin Registration Management Routes
    Route::get('/manage/registrations', [App\Http\Controllers\RegistrationController::class, 'index'])->name('registrations.index');

    // Admin Feedback & Evaluation Routes
    Route::get('/manage/feedback', [App\Http\Controllers\FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/manage/evaluations', [App\Http\Controllers\EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/manage/evaluations/{event}', [App\Http\Controllers\EvaluationController::class, 'show'])->name('evaluations.show');
    
    // Feedback Categories Routes
    Route::get('/manage/feedback/categories', [App\Http\Controllers\FeedbackCategoryController::class, 'index'])->name('feedback.categories.index');
    Route::get('/manage/feedback/categories/create', [App\Http\Controllers\FeedbackCategoryController::class, 'create'])->name('feedback.categories.create');
    Route::post('/manage/feedback/categories', [App\Http\Controllers\FeedbackCategoryController::class, 'store'])->name('feedback.categories.store');
    Route::get('/manage/feedback/categories/{id}', [App\Http\Controllers\FeedbackCategoryController::class, 'show'])->name('feedback.categories.show');
    Route::get('/manage/feedback/categories/{id}/edit', [App\Http\Controllers\FeedbackCategoryController::class, 'edit'])->name('feedback.categories.edit');
    Route::put('/manage/feedback/categories/{id}', [App\Http\Controllers\FeedbackCategoryController::class, 'update'])->name('feedback.categories.update');
    Route::delete('/manage/feedback/categories/{id}', [App\Http\Controllers\FeedbackCategoryController::class, 'destroy'])->name('feedback.categories.destroy');
    
    // Feedback AI Recommendations API
    Route::post('/api/feedback/recommendations', [App\Http\Controllers\FeedbackRecommendationController::class, 'generateRecommendation'])->name('api.feedback.recommendations');

});


// Feedback and evaluations are now managed via custom routes above
Route::resource('ressources', App\Http\Controllers\RessourceController::class);
Route::resource('fournisseurs', App\Http\Controllers\FournisseurController::class);
Route::resource('inscriptions', App\Http\Controllers\InscriptionController::class);
Route::resource('partenaires', App\Http\Controllers\PartenaireController::class);
Route::resource('sponsoring', App\Http\Controllers\SponsoringController::class);
















Route::get('/dashboard-Fournisseur', function () {
    return view('dashboard-Fournisseur.dashboard');
})->name('dashboard-Fournisseur');





// Routes pour suggestions et export (déplacées ici pour être définies tôt, avant tout Blade qui les référence)
Route::post('/events/suggest-resources', [\App\Http\Controllers\EventController::class, 'suggestResources'])->name('events.suggest-resources');
Route::get('/events/suggest-resources', function() {
    return response()->json(['error' => 'Use POST method with JSON body: {"categorie_id":1,"capacity_max":180}']);
});
Route::get('/events/export-history', [\App\Http\Controllers\EventController::class, 'exportHistory']);

// Stripe Webhook Route (no CSRF protection)
Route::post('/stripe/webhook', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';
