<?php
Route::get('/', 'ProjectController@index');
Route::get('/all-project', 'ProjectController@index');
Route::get('/create-project', 'ProjectController@create');
Route::post('/store-project', 'ProjectController@store');
Route::get('/edit-project', 'ProjectController@edit');
Route::post('/update-project', 'ProjectController@update');
Route::post('/delete-project', 'ProjectController@destroy');


Route::get('/project/{project_id}', 'FormController@index');
Route::get('/create-form/{project_id}', 'FormController@create');
Route::get('/show-form/{id}', 'FormController@show');
Route::post('/store-form', 'FormController@store');
Route::get('/edit-form', 'FormController@edit');
Route::post('/update-form', 'FormController@update');
Route::post('/delete-project', 'FormController@destroy');


Route::post('/export-project', 'FormController@exportProject');
Route::post('/export-form', 'FormController@exportForm');


Route::post('/ajax-check-form-name', 'FormController@ajaxCheckFormName');

Auth::routes();