<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user-authenticated', 'Api\UserController@getUserLogin')->name('user.authenticated');
    Route::get('notifications', 'Api\UserController@notification')->name('user.notification');

    Route::post('role-permission', 'Api\RolePermissionController@getRolePermission')->name('role_permission');
    Route::post('set-role-permission', 'Api\RolePermissionController@setRolePermission')->name('set_role_permission');
    Route::post('set-role-user', 'Api\RolePermissionController@setRoleUser')->name('user.set_role');

    Route::resource('/permissions', 'Api\PermissionController');
    Route::get('permission-list', 'Api\PermissionController@loadAll');
    Route::resource('/roles', 'Api\RoleController');
    Route::get('role-list', 'Api\RoleController@loadAll');
    Route::resource('/users', 'Api\UserController');
    Route::get('user-list', 'Api\UserController@loadAll');
});