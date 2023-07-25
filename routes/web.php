<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [Web::class, 'login']);
Route::get('login', [Web::class, 'login'])->name('login');
Route::post('auth/login', [Web::class, 'auth']);
Route::get('logout', [Web::class, 'logout']);
Route::get('print/{id}', [Web::class, 'print']);

Route::group(['middleware' => 'auth'], function () {
	Route::get('admin/produsen', [Web::class, 'produsen']);
	Route::post('admin/produsen', [Web::class, 'add_produsen']);
	Route::post('admin/produsen/save', [Web::class, 'save_produsen']);
	Route::get('admin/produsen/fetch/{id}', [Web::class, 'fetch_produsen']);
	Route::get('admin/produsen/{id}', [Web::class, 'delete_produsen']);
	Route::get('admin/user', [Web::class, 'user']);
	Route::post('admin/user', [Web::class, 'add_agent']);
	Route::post('admin/user/save', [Web::class, 'save_agent']);
	Route::get('admin/user/fetch/{id}', [Web::class, 'fetch_user']);
	Route::get('admin/user/{id}', [Web::class, 'delete_agent']);
	Route::get('admin/supplier', [Web::class, 'supplier']);
	Route::post('admin/supplier', [Web::class, 'add_supplier']);
	Route::post('admin/supplier/edit', [Web::class, 'edit_supplier']);
	Route::get('admin/supplier/{id}', [Web::class, 'delete_supplier']);
	Route::get('admin/supplier/fetch/{id}', [Web::class, 'fetch_supplier']);
	Route::get('admin/agent', [Web::class, 'agent']);
	Route::post('admin/agent', [Web::class, 'add_agents']);
	Route::post('admin/agent/edit', [Web::class, 'edit_agent']);
	Route::get('admin/agent/{id}', [Web::class, 'deleting_agent']);
	Route::get('admin/agent/fetch/{id}', [Web::class, 'fetch_agent']);
	Route::get('admin/deposit/{id}', [Web::class, 'fetch_deposit']);
	Route::get('admin/deposit/delete/{id}', [Web::class, 'delete_deposit']);
	Route::post('admin/deposit', [Web::class, 'add_deposit']);
	Route::get('admin/report', [Web::class, 'report']);
	Route::get('admin/manage', [Web::class, 'manage']);
	Route::get('admin/manage/acc/{id}', [Web::class, 'acc_sell']);
	Route::get('admin/manage/deny/{id}', [Web::class, 'deny_sell']);
	Route::post('change_password', [Web::class, 'change_password']);
	Route::get('admin/view/{id}', [Web::class, 'view_info']);
});



