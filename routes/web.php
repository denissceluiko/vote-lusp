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

Route::get('/', 'HomeController@landing');

Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'as' => 'admin.'], function() {
    Route::prefix('faculty')->group(function () {
        Route::get('/', 'FacultyController@index')->name('faculty.index');
        Route::post('import', 'FacultyController@import');
        Route::get('{faculty}', 'FacultyController@show')->name('faculty.show');
        Route::get('{faculty}/studentList', 'FacultyController@studentList')->name('faculty.studentList');
    });

    Route::prefix('election')->group(function () {
        Route::get('/', 'ElectionController@index')->name('election.index');
        Route::post('import', 'ElectionController@import');
        Route::get('{election}', 'ElectionController@show')->name('election.show');
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
        Route::get('{party}/ballot', 'PartyController@ballot')->name('party.ballot');
    });
    Route::resource('party', 'PartyController', ['except' => ['create', 'destroy']]);

    Route::resource('program', 'ProgramController', ['only' => ['index', 'show']]);
    Route::prefix('program')->group(function () {
        Route::post('import', 'ProgramController@import');
        Route::get('{program}/studentList', 'ProgramController@studentList')->name('program.studentList');
    });
});



Route::prefix('voter')->group(function () {
    Route::get('create', 'VoterController@create');
    Route::post('register', 'VoterController@register');
    Route::get('register', function () {
        return redirect('/');
    });
});

Route::prefix('election')->group(function () {
    Route::post('{election}/register', 'ElectionController@register')->name('election.register');
    Route::get('{election}/register', 'ElectionController@registration')->name('election.registration');
    Route::get('{election}', 'ElectionController@show')->name('election.show');
});

Route::prefix('vote')->group(function () {
    Route::get('{ballot}', 'BallotController@show');
    Route::post('{ballot}', 'BallotController@auth');
    Route::post('{ballot}/select', 'BallotController@selectParty');
    Route::post('{ballot}/vote', 'BallotController@vote');
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
