<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {  return 'OK'; });
Route::get('/clients-ui', fn () => view('clients-ui'));