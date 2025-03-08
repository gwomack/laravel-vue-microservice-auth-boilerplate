<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Add a test endpoint for authentication
    Route::get('/auth-test', function () {
        return response()->json([
            'message' => 'You are authenticated!',
            'user' => auth()->user()
        ]);
    });
});

// Test endpoint to dispatch authentication events to other services
Route::post('/auth-event', function (Request $request) {
    // Dispatch authentication event to other services via RabbitMQ
    TestRabbitMQJob::dispatch([
        'event' => 'user.authenticated',
        'user_id' => auth()->id(),
        'timestamp' => now()->toIso8601String()
    ]);
    
    return response()->json(['message' => 'Auth event dispatched']);
})->middleware(['auth']);

// Test route for RabbitMQ
Route::get('/test-queue', function () {
    // Dispatch a test job
    TestRabbitMQJob::dispatch('Hello from RabbitMQ!');
    return 'Job dispatched!';
});

require __DIR__.'/auth.php';
