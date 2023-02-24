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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['jwt']], function() {
	Route::namespace('App\Http\Controllers')->group(static function() {
		
	    Route::post('/logout', 'AuthController@logout');
    	Route::post('/refresh', 'AuthController@refresh');
    	Route::get('/me', 'AuthController@me');

    	Route::prefix('users')->group(function () {
	    	Route::get('/', 'UserController@index');
	   		Route::post('/', 'UserController@store');
	   		Route::post('/{id}', 'UserController@update');
	   		Route::put('/{id}', 'UserController@active');
			Route::get('/{id}', 'UserController@show');
	   		Route::delete('/{id}', 'UserController@destroy');
		});

		Route::prefix('banks')->group(function () {
	   		Route::post('/', 'BankController@store');
	   		Route::post('/{id}', 'BankController@update');
			
	   		Route::delete('/{id}', 'BankController@destroy');
		});
	});
});

Route::namespace('App\Http\Controllers')->group(static function() {
	Route::post('/login', 'AuthController@login');

	Route::prefix('banks')->group(function () {
    	Route::get('/', 'BankController@index');
    	Route::get('/{id}', 'BankController@show');
    	Route::get('/byCountry/{country}', 'BankController@byCountry');
   	});

	Route::prefix('countries')->group(function () {
    	Route::get('/', 'CountryController@index');
   	});

   	Route::prefix('transactions')->group(function () {
    	Route::get('/resumen/{id}', 'TransactionController@get_resumen');
    	Route::get('/rates', 'TransactionController@get_rate');
    	Route::get('/rates/{currency_id}', 'TransactionController@get_rate_by_currency');
    	Route::post('/originPayments', 'TransactionController@store_payments_origin');
    	Route::post('/destinationPayments', 'TransactionController@store_destination_payments');
		Route::post('/attachment', 'TransactionController@uploads_attachment');
   	});
});
