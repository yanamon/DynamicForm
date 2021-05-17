<?php
Route::get('/', 'ProjectController@index');
Route::get('/all-project', 'ProjectController@index');
Route::get('/create-project', 'ProjectController@create');
Route::post('/store-project', 'ProjectController@store');
Route::get('/edit-project/{id}', 'ProjectController@edit');
Route::post('/update-project', 'ProjectController@update');
Route::post('/delete-project/{id}', 'ProjectController@destroy');


Route::get('/project/{project_id}/forms', 'FormController@index');
Route::get('/create-form/{project_id}', 'FormController@create');
Route::get('/show-form/{id}', 'FormController@show');
Route::post('/store-form', 'FormController@store');
Route::get('/edit-form/{id}', 'FormController@edit');
Route::post('/update-form', 'FormController@update');
Route::post('/delete-form/{id}', 'FormController@destroy');
Route::get('/change-menu-index/{id}/{change_direction}', 'FormController@change_menu_index');



Route::get('/form/{form_id}/sub-forms', 'SubFormController@index');
Route::get('/create-sub-form/{id}', 'SubFormController@create');
Route::post('/store-sub-form', 'SubFormController@store');


Route::get('/export-project/{id}', 'FormController@exportProject');
// Route::get('/export-form/{id}', 'FormController@exportForm');


Route::post('/ajax-check-form-name', 'FormController@ajaxCheckFormName');
Route::post('/ajax-check-sub-form-name', 'SubFormController@ajaxCheckSubFormName');

Auth::routes();