<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('description', 'Api\DescriptionController@show')->name('apidescription');
Route::post('definition', 'Api\DefinitionController@show')->name('apidefinition');
Route::post('position', 'Api\PositionController@show')->name('apiposition');
Route::post('dependency', 'Api\DependencyController@show')->name('apidependency');
Route::post('plan', 'Api\PlanController@show')->name('apiplan');
Route::get('/gantt/{id}', 'Api\GanttController@show')->name('apigantt');
