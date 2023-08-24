<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ContactNoteController;

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

function getContacts()
{
    return [
        1 => ['name' => 'Name 1', 'phone' => '1234567890'],
        2 => ['name' => 'Name 2', 'phone' => '2345678901'],
        3 => ['name' => 'Name 3', 'phone' => '3456789012'],
    ];
}

Route::get('/', function () {
    return view('welcome');
})->name('main');

Route::controller(ContactController::class)->name('contacts.')->group(function (){
    Route::get('/contacts',  'index')->name('index');
    Route::get('/contacts/create',  'create')->name('create');
    Route::get('/contacts/{id}',  'show')->name('show');

});


Route::resources([
    '/companies' => CompanyController::class,
    '/tasks' => TaskController::class,
    '/tags' => TagController::class,
    '/activities' => ActivityController::class,
]);

Route::resource('/contacts.notes', ContactNoteController::class)->shallow();


route::fallback(function () {
    return "<h1>Sorry, This Page Not Exist!!";
});
