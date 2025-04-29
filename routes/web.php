<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TypeFormController;
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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
