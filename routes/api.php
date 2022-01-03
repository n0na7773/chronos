<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\EventController;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::prefix('users')->group(function () {
    Route::get('', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::prefix('calendars')->group(function () {
    Route::post('/', [CalendarController::class, 'createCalendar']);
    Route::get('/getCalendarsForUser', [CalendarController::class, 'getCalendarsForUser']);
    Route::get('/getUsersForCalendar/{id}', [CalendarController::class, 'getUsersForCalendar']);
    Route::get('/{id}', [CalendarController::class, 'getCalendar']);
    Route::patch('/{id}', [CalendarController::class, 'update']);
    Route::delete('/{id}', [CalendarController::class, 'destroy']);
});

Route::prefix('events')->group(function () {
    Route::post('/get/{id}', [EventController::class, 'createEventForCalendar']);
    Route::get('/{id}', [EventController::class, 'getEventsForCalendar']);
    Route::get('/one/{id}', [EventController::class, 'getEventById']);
    Route::delete('/one/{id}', [EventController::class, 'delEventById']);
});
