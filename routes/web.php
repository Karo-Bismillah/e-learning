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


Route::group(['prefix' => '/admin/teacher'], function () {
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

/* Route Student */
Route::group(['prefix' => '/admin/student'], function () {
    /* Route GET */
    Route::get('/', 'Administrator\StudentController@index')->name('indexStudent');
    Route::get('/edit/{id}', 'Administrator\StudentController@edit');
    Route::get('/delete/{id}', 'Administrator\StudentController@delete');

    /* Route POST */
    Route::post('/create-student', 'Administrator\StudentController@create')->name('createStudent');
    Route::post('/update-student', 'Administrator\StudentController@update')->name('updateStudent');

    /* DataTable */
    Route::get('/json-student', 'Administrator\StudentController@dataTable')->name('jsonStudent');
    /* Dropdown json */
    Route::get('/list-classroom', 'Administrator\StudentController@classroom')->name('listClassroom');
});

/* Route Classroom */
Route::group(['prefix' => '/admin/classroom'], function () {
    /* Route GET */
    Route::get('/', 'Administrator\ClassroomController@index')->name('indexClassroom');
    Route::get('/edit/{id}', 'Administrator\ClassroomController@edit');
    Route::get('/delete/{id}', 'Administrator\ClassroomController@delete');

    /* Route POST */
    Route::post('/create-classroom', 'Administrator\ClassroomController@create')->name('createClassroom');
    Route::post('/update-classroom', 'Administrator\ClassroomController@update')->name('updateClassroom');

    /* DataTable */
    Route::get('/json-classroom', 'Administrator\ClassroomController@dataTable')->name('jsonClassroom');
    /* Dropdown json */
    Route::get('/list-teacher', 'Administrator\ClassroomController@teacher')->name('listTeacher');
});

/* Route Subject Matter */
Route::group(['prefix' => '/admin/subjectmatter'], function () {
    /* Route GET */
    Route::get('/', 'Administrator\SubjectMatterController@index')->name('indexSubjectMatter');
    Route::get('/edit/{id}', 'Administrator\SubjectMatterController@edit');
    Route::get('/delete/{id}', 'Administrator\SubjectMatterController@delete');

    /* Route POST */
    Route::post('/create-subjectmatter', 'Administrator\SubjectMatterController@create')->name('createSubjectMatter');
    Route::post('/update-subjectmatter', 'Administrator\SubjectMatterController@update')->name('updateSubjectMatter');

    /* DataTable */
    Route::get('/json-subjectmatter', 'Administrator\SubjectMatterController@dataTable')->name('jsonSubjectMatter');
    /* Dropdown json */
    Route::get('/list-course', 'Administrator\SubjectMatterController@course')->name('listCourse');
});
