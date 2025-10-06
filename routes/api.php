<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ClientController; 

Route::get('ping', fn () => response()->json(['pong' => true]));
Route::apiResource('clients', ClientController::class); 