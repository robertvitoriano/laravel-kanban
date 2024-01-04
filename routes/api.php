<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('login',[UserController::class, 'login']);
Route::post('register',[UserController::class, 'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){
    Route::patch('users/update-profile', [UserController::class, 'updateProfile']);
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('boards', BoardController::class);
    Route::get('/project-lists/board/{board_id}', [ProjectListController::class, 'getProjectListsByBoard']);
    Route::apiResource('project-lists',ProjectListController::class);
    Route::post('/boards/create-membership', [BoardController::class, 'createBoardMembership']);
    Route::post('/projects/members',[MemberController::class, 'store']);
});

