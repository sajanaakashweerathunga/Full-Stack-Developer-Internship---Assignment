<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::prefix('student')->group(function (){
    Route::get('/', [StudentController::class, 'index'])->name('student.index');
    Route::get('/getdata', [StudentController::class, 'getdata'])->name('student.getdata');
    Route::get('/create', [StudentController::class, 'create'])->name('student.create');
    Route::post('/store', [StudentController::class, 'store'])->name('student.store');
    Route::get('/edit/{id}', [StudentController::class, 'edit'])->name('student.edit');
    Route::post('/update', [StudentController::class, 'update'])->name('student.update');
    Route::delete('/delete/{id}', [StudentController::class, 'delete'])->name('student.delete');
    Route::put('/students/{id}/toggle-status', [StudentController::class, 'toggleStatus'])->name('student.toggleStatus');
});


require __DIR__.'/auth.php';
