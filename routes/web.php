<?php

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
    return view('welcome');
});

Route::prefix('faculty')->group(function () {
    Route::get('/', 'FacultyController@index')->name('faculty.index');
    Route::post('import', 'FacultyController@import');
    Route::get('{faculty}', 'FacultyController@show')->name('faculty.show');
});

Route::prefix('program')->group(function () {
    Route::post('import', 'ProgramController@import');
});

Route::prefix('student')->group(function () {
    Route::post('import', 'StudentController@import');
});

Route::prefix('candidate')->group(function () {
    Route::post('import', 'CandidateController@import');
});

Route::prefix('party')->group(function () {
    Route::get('/', 'PartyController@index')->name('party.index');
    Route::get('{party}', 'PartyController@show')->name('party.show');
});

//Auth::routes(['register' => false]);
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
