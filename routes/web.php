<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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
    return view('dashboard');
})->middleware(['auth']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//User
Route::group(["as" => "profile.", "prefix" => "profile", "middleware" => ["auth"]], function(){
    Route::get("/edit", [UserController::class, "edit"])->name("edit");
    Route::post("/update", [UserController::class, "update"])->name("update");
});

//Admin
Route::group(["as" => "admin.","middleware" => ["auth", "admin"]], function(){
    Route::get("/users/index", [UserController::class, "index"])->name("users");
    Route::post("/user/store", [UserController::class, "store"])->name("user.store");
    Route::patch("/user/delete/{user:id}", [UserController::class, "destroy"])->name("user.delete");
});
require __DIR__.'/auth.php';
require __DIR__.'/api.php';
