<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/login-ui', 'login');
Route::view('/dashboard', 'dashboard');
Route::view('/jobs-ui', 'jobs.index');
Route::view('/applications-ui', 'applications.index');