<?php
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('foo', function () {
    return 'Hello World';
});

Route::get('/hello', 'HelloController@index');

//获取url
//p($_SERVER['REQUEST_URI']);

//接口
Route::group(['namespace' => 'Openapi', 'prefix' => 'openapi'], function () {
    //接口入口
    Route::get('', 'IndexController@index');
    //验证访问码 接口
    Route::any('login', 'LoginController@verify');
    //获取图片验证码 接口
    Route::get('code', 'LoginController@code');

});

Route::group(['middleware' => ['web','openapi.login'],'namespace' => 'Openapi', 'prefix' => 'openapi'], function () {

    //获取汇总数据
    Route::get('summary', 'SummaryController@get');
    //获取排名数据
    Route::get('rank', 'RankController@get');

});

// Reptile 模块
Route::group(['namespace' => 'Reptile', 'prefix' => 'reptile'], function () {
    // 爬虫管理
    Route::group(['prefix' => 'task'], function () {
        Route::get('index', 'TaskController@index');
    });
    // 派件管理
    Route::group(['prefix' => 'waybill'], function () {
        Route::get('index', 'WaybillController@worker_waybill');
    });
    //揽件管理
    Route::group(['prefix' => 'shipment'], function () {
        Route::get('index', 'ShipmentController@worker_shipment');
    });
    //到付代收管理
    Route::group(['prefix' => 'cod'], function () {
        Route::get('index', 'CodController@worker_cod');
    });
    //访问码更新
    Route::group(['prefix' => 'accesscode'], function () {
        Route::get('index', 'AccessCodeController@index');
    });

});

