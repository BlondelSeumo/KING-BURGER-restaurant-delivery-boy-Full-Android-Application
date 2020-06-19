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
Route::get('locale/{locale}', function ($locale){
    Session::put('locale', $locale);
    return redirect()->back();
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::any("register","api\ApiController@postregister");//register
Route::any("tokan_data","api\ApiController@savetoken");//store token
Route::any("getcity","api\ApiController@getcity");//get city list
Route::any("deliveryboy_login","api\ApiController@deliveryboy_login");//delivery login
Route::any("deliveryboy_order","api\ApiController@deliveryboy_order");//delivery order
Route::post("deliveryboy_presence","api\ApiController@deliveryboy_presence");
Route::any("deliveryboy_profile","api\ApiController@deliveryboy_profile");

Route::any("ingredients","api\ApiController@ingredients");
Route::any("menu_category","api\ApiController@menucategory");
Route::any("noOfOrder","api\ApiController@noOfOrder");
Route::any("orderdetails","api\ApiController@orderdetails");
Route::any("subcategory","api\ApiController@subcategory");
Route::any("order_history","api\ApiController@order_history");
Route::any("order_cancel","api\ApiController@order_cancel");
Route::any("order_complete","api\ApiController@order_complete");
Route::any("order_pick","api\ApiController@order_pick");
Route::any("order_item_details","api\ApiController@order_item_details");
Route::post("food_order","api\ApiController@food_order");
Route::any("order_details","api\ApiController@order_details");
Route::any("forgotpassword","api\ApiController@forgotpassword");
Route::get("getsetting",'api\ApiController@getsetting');




