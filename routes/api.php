<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/projects', ProjectController::class . '@store')->name('projects.store');
Route::get('/projects', ProjectController::class . '@index')->name('projects.index');