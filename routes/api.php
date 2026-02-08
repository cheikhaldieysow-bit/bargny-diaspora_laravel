<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\ProjectController;
>>>>>>> df3d086 (Delete a project)
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
<<<<<<< HEAD
=======

// La route pour la suppression d'un projet s'il n'est pas financé
Route::middleware('auth:sanctum')
    ->delete('/projects/{project}', [ProjectController::class, 'destroy']);
>>>>>>> df3d086 (Delete a project)
