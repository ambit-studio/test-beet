<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/report', 'DataController@showTable');

Route::post('file', 'DataController@fileToReport');
