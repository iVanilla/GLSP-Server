<?php

use App\Http\Controllers;
use \NoahBuscher\Macaw\Macaw as Route;

/* ---------- Auth ---------- */
Route::post('/main.php/login/authkey', 'MainController@auth');
Route::post('/main.php/login/startUp', 'MainController@startup');
Route::post('/main.php/login/login', 'MainController@login');
Route::post('/main.php/login/startSetInvite', 'MainController@startinv');
Route::post('/main.php/login/startWithoutInvite', 'MainController@startnoinv');

/* ---------- Maintenance ---------- */
Route::get('/resources/maintenace/maintenance.php', 'App\Http\Controllers\MainController@maintenance');

/* ---------- Databases ---------- */
//Route::get('/db/');

Route::dispatch();