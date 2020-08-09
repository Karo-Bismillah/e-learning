<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'teacher'], function () {
        /* Route GET */
        Route::get('/', 'Administrator\TeacherController@index')->name('indexTeacher');
        Route::get('/edit/{id}', 'Administrator\TeacherController@edit');
        Route::get('/delete/{id}', 'Administrator\TeacherController@delete');

        /* Route POST */
        Route::post('/create-teacher', 'Administrator\TeacherController@create')->name('createTeacher');
        Route::post('/update-teacher', 'Administrator\TeacherController@update')->name('updateTeacher');

        /* DataTable */
        Route::get('/json-teacher', 'Administrator\TeacherController@dataTable')->name('jsonTeacher');
    });
});
