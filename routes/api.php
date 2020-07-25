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

Route::group(['middleware' => 'auth:api'], function(){

    Route::get('/dashboard', 'api\DashboardController@summary');

    Route::group(['prefix' => 'transaksi', 'namespace' => 'api\trans'], function(){

        Route::get('member/aktif', 'TransaksiBukuController@activeMembers');


        Route::get('buku/pinjam/list', 'TransaksiBukuController@borrowedBooks');
        Route::get('buku/pinjam/{id_book}', 'TransaksiBukuController@borrowedDetail');
        Route::post('buku/pinjam/{id_book}', 'TransaksiBukuController@borrowBook');
        Route::get('buku/tersedia', 'TransaksiBukuController@availableBooks');

        Route::post('buku/kembalikan/{id}', 'TransaksiBukuController@returnBook');

        Route::get('buku/laporan/list', 'TransaksiBukuController@borrowedReport');

    });

    Route::group(['prefix' => 'master-data', 'namespace' => 'api\master'], function(){

        Route::apiResources([
            'user' => 'UserController',
            'member' => 'MemberController',
            'buku' => 'BukuController',
        ]);

    });

});
