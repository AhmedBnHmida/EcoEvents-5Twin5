<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\EventController;  // Ajoute ce use pour EventController
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\FeedbackCategoryController;
use App\Http\Controllers\FeedbackRecommendationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RessourceController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\PartenaireController;
use App\Http\Controllers\SponsoringController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Http;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard API endpoints
Route::middleware('auth')->prefix('api')->group(function () {
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
    Route::get('/dashboard/event-stats', [DashboardController::class, 'getEventStats'])->name('api.dashboard.event-stats');
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
Route::resource('users', UsersController::class);

// Public routes - no conflict
Route::get('/events', [EventController::class, 'publicIndex'])->name('events.public');
Route::get('/events/{id}', [EventController::class, 'publicShow'])->name('events.public.show');

// Participant Registration Routes (create can be accessed by guests, will redirect to login)
Route::get('/registrations/create', [RegistrationController::class, 'create'])->name('registrations.create');

Route::middleware('auth')->group(function () {
    Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/registrations/{registration}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::delete('/registrations/{registration}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    
    // Payment Routes
    Route::get('/payment/checkout/{registration}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{registration}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{registration}', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // Feedback Routes (Participants)
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::get('/my-feedbacks', [FeedbackController::class, 'myFeedbacks'])->name('feedback.my');
});

// ADMIN Routes - Use explicit routes instead of resource to avoid conflicts
Route::middleware('auth')->group(function () {
    // QR Code Scanning Routes
    Route::get('/qrscan', [App\Http\Controllers\QrScanController::class, 'showScanPage'])->name('qrscan.show');
    Route::post('/qrscan/process', [App\Http\Controllers\QrScanController::class, 'processScan'])->name('qrscan.process');
    Route::post('/qrscan/mark-attended', [App\Http\Controllers\QrScanController::class, 'markAsAttended'])->name('qrscan.mark-attended');
    // Admin Events Routes
    Route::get('/manage/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/manage/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/manage/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/manage/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/manage/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/manage/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/manage/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');

    // Categories Routes
    Route::get('/manage/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/manage/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/manage/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/manage/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');
    Route::get('/manage/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/manage/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/manage/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Registration Management Routes
    Route::get('/manage/registrations', [RegistrationController::class, 'index'])->name('registrations.index');

    // Admin Feedback & Evaluation Routes
    Route::get('/manage/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/manage/evaluations', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/manage/evaluations/{event}', [EvaluationController::class, 'show'])->name('evaluations.show');
    
    // Feedback Categories Routes
    Route::get('/manage/feedback/categories', [FeedbackCategoryController::class, 'index'])->name('feedback.categories.index');
    Route::get('/manage/feedback/categories/create', [FeedbackCategoryController::class, 'create'])->name('feedback.categories.create');
    Route::post('/manage/feedback/categories', [FeedbackCategoryController::class, 'store'])->name('feedback.categories.store');
    Route::get('/manage/feedback/categories/{id}', [FeedbackCategoryController::class, 'show'])->name('feedback.categories.show');
    Route::get('/manage/feedback/categories/{id}/edit', [FeedbackCategoryController::class, 'edit'])->name('feedback.categories.edit');
    Route::put('/manage/feedback/categories/{id}', [FeedbackCategoryController::class, 'update'])->name('feedback.categories.update');
    Route::delete('/manage/feedback/categories/{id}', [FeedbackCategoryController::class, 'destroy'])->name('feedback.categories.destroy');
    
    // Feedback AI Recommendations API
    Route::post('/api/feedback/recommendations', [FeedbackRecommendationController::class, 'generateRecommendation'])->name('api.feedback.recommendations');
});

// Route pour suggestions de ressources (FIX : namespace complet)
Route::post('/suggest-resources', [App\Http\Controllers\EventController::class, 'suggestResources'])->name('events.suggest-resources');

// Feedback and evaluations are now managed via custom routes above
Route::resource('ressources', RessourceController::class);
Route::resource('fournisseurs', FournisseurController::class);
Route::resource('inscriptions', InscriptionController::class);
Route::resource('partenaires', PartenaireController::class);
Route::resource('sponsoring', SponsoringController::class);

Route::get('/dashboard-Fournisseur', function () {
    return view('dashboard-Fournisseur.dashboard');
})->name('dashboard-Fournisseur');

// Routes pour suggestions et export (FIX : namespace complet)
Route::get('/events/export-history', [App\Http\Controllers\EventController::class, 'exportHistory']);

// Stripe Webhook Route (no CSRF protection)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

require __DIR__.'/auth.php';