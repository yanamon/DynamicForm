<?php
Route::get('/', 'FormController@index');
Route::post('/store-form', 'FormController@store');
Route::get('show-all-form', 'FormController@showAll');


Route::get('show-form/{id}', 'FormUserController@show');
Route::post('/store-user-form', 'FormUserController@store');


Auth::routes();