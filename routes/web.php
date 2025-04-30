<?php

<<<<<<< HEAD
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
=======
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeFormController;
>>>>>>> 45317f6ee1f3cafab5591d94cd41d61a217a2f64
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
Route::middleware(['auth','verified'])->group(function(){
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('product')->name('product.')->group(function(){
        Route::get('/',[ProductController::class,'index'])->name('index');

        Route::middleware('isAdmin')->group(function(){
            Route::get('/create',[ProductController::class,'create'])->name('create');
            Route::post('/',[ProductController::class,'store'])->name('store');
        });
    });  

});





=======
Route::get('/notification-database',[HomeController::class,'databaseNotification']);
Route::get('/notification-database-read/{id}',[HomeController::class,'markAsRead'])->name('markAsRead');

Route::get('/sms-notification',[HomeController::class,'smsNotification'])->name('sms.notification');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('typeform')->name('typeform.')->group(function(){
    Route::get('form',[TypeFormController::class,'index'])->name('form');
    Route::get('getForm/{formId}',[TypeFormController::class,'getForm'])->name('form.get');
    Route::post('form/edit',[TypeFormController::class,'edit'])->name('form.edit');
});

>>>>>>> 45317f6ee1f3cafab5591d94cd41d61a217a2f64
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
