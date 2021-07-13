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

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@authenticate');
Route::get('/user/quarters/{id}',[
   //'middleware' => ['check_role:master,administrador,consultor'],
   'uses' => 'UserController@quarters',
]);
Route::group(['middleware' => ['jwt.verify']], function() {
    // Auth
    Route::get('/auth/user','AuthController@getAuthenticatedUser');
    // user
    Route::get('/user/roles', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'UserController@roles',
    ]);
    Route::get('/user/all', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'UserController@all',
    ]);
    Route::get('/user/{id}', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'UserController@get',
    ]);
    Route::post('/user/store', [
       'middleware' => ['check_role:master'],
       'uses' => 'UserController@store',
    ]);
    Route::delete('/user/delete/{id}',[
       'middleware' => ['check_role:master'],
       'uses' => 'UserController@delete',
    ]);
    Route::post('/user/update/{id}',[
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'UserController@update',
    ]);
    Route::get('/user/farms/{id}',[
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'UserController@farms',
    ]);

    Route::get('/user/clients/{id}',[
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'UserController@clients',
   ]);

   Route::get('/user/clientsFarm/{id}',[
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'UserController@farmsClient',
   ]);
    
    // cliente 
   Route::get('/client/all', [
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'ClientController@all',
   ]);
   Route::get('/client/{id}', [
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'ClientController@get',
   ]);
   

   Route::post('/client/store', [
      'middleware' => ['check_role:master'],
      'uses' => 'ClientController@store',
   ]);
   Route::delete('/client/delete/{id}',[
      'middleware' => ['check_role:master'],
      'uses' => 'ClientController@delete',
   ]);
   Route::post('/client/update/{id}',[
      'middleware' => ['check_role:master,administrador'],
      'uses' => 'ClientController@update',
   ]);

   Route::get('/client/clientfarm/{id}', [
      'middleware' => ['check_role:master,administrador'],
      'uses' => 'ClientController@getFarmClient',
   ]);
    
    // employee
    Route::get('/employee/all', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'EmployeeController@all',
    ]);
    Route::get('/employee/{id}', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'EmployeeController@get',
    ]);
    Route::post('/employee/store', [
       'middleware' => ['check_role:master'],
       'uses' => 'EmployeeController@store',
    ]);
    Route::delete('/employee/delete/{id}', [
       'middleware' => ['check_role:master'],
       'uses' => 'EmployeeController@delete',
    ]);
    Route::post('/employee/update/{id}',[
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'EmployeeController@update',
    ]); 
    Route::get('/employee/seasons/{id}', [
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'EmployeeController@seasons',
    ]);
    
    // farm
    Route::get('/farm/all',[
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'FarmController@all',
    ]);
    Route::get('/farm/{id}', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'FarmController@get',
    ]);
    Route::post('/farm/store', [
       'middleware' => ['check_role:master'],
       'uses' => 'FarmController@store',
    ]);
    Route::delete('/farm/delete/{id}', [
       'middleware' => ['check_role:master'],
       'uses' => 'FarmController@delete',
    ]);
    Route::post('/farm/update/{id}', [
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'FarmController@update',
    ]);
    // quarter
    Route::get('/quarter/all', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'QuarterController@all',
    ]);
    Route::get('/quarter/{id}',[
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'QuarterController@get',
    ]);
    Route::post('/quarter/store', [
       'middleware' => ['check_role:master'],
       'uses' => 'QuarterController@store',
    ]);
    Route::delete('/quarter/delete/{id}', [
       'middleware' => ['check_role:master'],
       'uses' => 'QuarterController@delete',
    ]);
    Route::post('/quarter/update/{id}',[
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'QuarterController@update',
    ]);

    Route::get('/quarter/quarterFarm/{id}',[
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'QuarterController@getFarm',
   ]);
    // count
    Route::get('/count/all',[
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'CountController@all',
    ]);
    Route::get('/count/allcard', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'CountController@cardData',
    ]);
    Route::get('/count/allcount', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'CountController@conteoRacimos',
    ]);
    Route::get('/count/getimportdates', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'CountController@getImportDates',
    ]);
    Route::get('/count/{id}', [
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'CountController@get',
    ]);
    Route::post('/count/getrecordsperbatch',[
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'CountController@getRecordsPerBatch',
    ]); 
    Route::post('/count/deleterecordsperbatch',[
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'CountController@deleteRecordsPerBatch',
    ]);
    Route::post('/count/store', [
       'middleware' => ['check_role:master'],
       'uses' => 'CountController@store',
    ]);
    Route::delete('/count/delete/{id}', [
       'middleware' => ['check_role:master'],
       'uses' => 'CountController@delete',
    ]);
    Route::post('/count/update/{id}', [
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'CountController@update',
    ]);

    // poda
    Route::get('/poda/all',[
       'middleware' => ['check_role:master,administrador,consultor'],
       'uses' => 'PodaController@all',
    ]);
      
   Route::get('/poda/{id}', [
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'PodaController@get',
   ]);
   Route::post('/poda/store', [
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'PodaController@store',
   ]);
   Route::delete('/poda/delete/{id}', [
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'PodaController@delete',
   ]);
   Route::post('/poda/update/{id}', [
      'middleware' => ['check_role:master,administrador'],
      'uses' => 'PodaController@update',
   ]);
   // season
   Route::get('/season/all', [
     'middleware' => ['check_role:master,administrador,consultor'],
     'uses' => 'SeasonController@all',
   ]);
   Route::get('/season/{id}',[
      'middleware' => ['check_role:master,administrador,consultor'],
      'uses' => 'SeasonController@get',
   ]); 
   Route::post('/season/store', [
      'middleware' => ['check_role:master,administrador'],
      'uses' => 'SeasonController@store',
   ]); 
    Route::delete('/season/delete/{id}', [
       'middleware' => ['check_role:master'],
       'uses' => 'SeasonController@delete',
    ]); 
    Route::post('/season/update/{id}', [
       'middleware' => ['check_role:master,administrador'],
       'uses' => 'SeasonController@update',
    ]);
});
