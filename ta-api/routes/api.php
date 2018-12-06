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

Route::group(array(), function() {
    Route::get('/', function () {
        return response()->json(['message' => 'T&A API', 'status' => 'Connected']);;
    });

    Route::post('auth/login', 'AuthController@login');
    Route::get('auth/logout', 'AuthController@logout');
    Route::post('auth/refresh', 'AuthController@refresh');
    Route::post('auth/me', 'AuthController@me');

    Route::get('tables/getAll', 'MesaController@index');
    Route::get('tables/get/{id}', 'MesaController@show');
    Route::get('tables/freeTables', 'MesaController@freeTables');

    Route::post('tables/create', 'MesaController@store');

    Route::put('tables/update/{id}', 'MesaController@update');

    Route::delete('tables/delete/{id}', 'MesaController@destroy');
    

    Route::resource('typeItens', 'TipoItemController');

    Route::resource('items', 'ItemController');

    Route::resource('users', 'UsuarioController');

    Route::resource('services', 'ServicoController');

    Route::resource('attendaces', 'AtendimentoController');
    Route::put('attendaces/free/{id}', 'AtendimentoController@freeTable');

    Route::resource('serviceAttendaces', 'ServicoAtendimentoController');

    Route::resource('itemsAttendaces', 'ItemAtendimentoController');
    
    
    Route::get('/', function () {
        return redirect('api');
    });
});


/* Route::get('tables/getAll', 'MesaController@index');
Route::get('/tables/get/{$id}', 'MesaController@show');

Route::post('/tables/create', 'MesaController@store');
Route::put('/tables/update', 'MesaController@update');

Route::delete('/tables/delete', 'MesaController@destroy'); */