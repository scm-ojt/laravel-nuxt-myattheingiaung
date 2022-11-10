<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;



Route::middleware('auth:sanctum')->get('/user',function(Request $request){
    return $request->user();
});

Route::get('post',[PostController::class, 'index']);
Route::post('post',[PostController::class,'store']);
Route::delete('post/{post}',[PostController::class, 'destroy']);
Route::post('post/{post}',[PostController::class,'update']);



Route::post('/login',LoginController::class);


//register
Route::post('/register',[RegisterController::class , 'store']);

//logout
Route::post('logout',[LoginController::class,'logout']);