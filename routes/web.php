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
    Route::get('{faculty}/studentList', 'FacultyController@studentList')->name('faculty.studentList');
});

Route::resource('program', 'ProgramController', ['only' => ['index', 'show']]);
Route::prefix('program')->group(function () {
    Route::post('import', 'ProgramController@import');
    Route::get('{program}/studentList', 'ProgramController@studentList')->name('program.studentList');
});

Route::prefix('student')->group(function () {
    Route::get('/', 'StudentController@index')->name('student.index');
    Route::get('search', 'StudentController@search');
    Route::post('import', 'StudentController@import');
    Route::get('{student}', 'StudentController@show')->name('student.show');
});

Route::prefix('candidate')->group(function () {
    Route::post('import', 'CandidateController@import');
});

Route::prefix('party')->group(function () {
    Route::get('/', 'PartyController@index')->name('party.index');
    Route::get('{party}', 'PartyController@show')->name('party.show');
    Route::get('{party}/ballot', 'PartyController@ballot')->name('party.ballot');
});

Route::prefix('protocol')->group(function () {
    Route::get('/', 'ProtocolController@index')->name('protocol.index');
    Route::get('{faculty}/create', 'ProtocolController@create')->name('protocol.create');
    Route::post('{faculty}/store', 'ProtocolController@store')->name('protocol.store');
    Route::get('{protocol}/fill', 'ProtocolController@fill')->name('protocol.fill');
    Route::post('{protocol}/save', 'ProtocolController@save')->name('protocol.save');
    Route::get('{protocol}/fillCandidates', 'ProtocolController@fillCandidates')->name('protocol.fillCandidates');
    Route::post('{protocol}/saveCandidates', 'ProtocolController@saveCandidates')->name('protocol.saveCandidates');
    Route::get('{protocol}/results', 'ProtocolController@results')->name('protocol.results');
});

Auth::routes(['register' => false]);
//Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
