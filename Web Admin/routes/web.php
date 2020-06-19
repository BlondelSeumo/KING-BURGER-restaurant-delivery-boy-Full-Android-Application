<?php

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



Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});
Route::get('/', function() {
    Artisan::call('config:cache');
   return redirect('admin');
});
Route::get('/routecache', function() {
    Artisan::call('route:clear');
    return "config is cleared";
});
Route::get("/admin","AuthenticationController@showlogin")->name("showlogin");
Route::post("postlogin","AuthenticationController@postlogin")->name("postlogin");
Route::get("logout","AuthenticationController@logout")->name("logout");

//website


Route::group(['middleware' => ['admincheckexiste']], function () {

	Route::get("dashboard","AuthenticationController@showdashboard")->name("showdashboard");
	Route::get("setting","AuthenticationController@editsetting");
	Route::post("updatesetting","AuthenticationController@updatesetting");

    Route::get("printorder/{id}","OrderController@print");
    Route::any("saveresdetail","AuthenticationController@saveresdetail");
    Route::any("savesoicalsetting","AuthenticationController@savesoicalsetting")->name("savesoicalsetting");
    Route::any("savepaymentdata","AuthenticationController@savepaymentdata");
	//menu category
	Route::get("category","CategoryController@showcategory")->name("showcategory");	
	Route::get("categorydatatable","CategoryController@categorydatatable")->name("categorydatatable");
	Route::post("add_menu_cateogry","CategoryController@addcateogry")->name("add_menu_cateogry");
	Route::get("deletemenu/{id}","CategoryController@deletemenu")->name("deletemenu");
	Route::get("editmenu/{id}","CategoryController@editmenu")->name("editmenu");
	Route::post("updatecategory","CategoryController@updatecategory")->name("updatecategory");
	Route::get("changesettingorderstatus/{status}","AuthenticationController@changesettingorderstatus");

	//menu item

	Route::get("menuitem","ItemController@index")->middleware('admincheckexiste');
	Route::get("itemdatatable","ItemController@itemdatatable")->name('itemdatatable');
	Route::post("add_menu_item","ItemController@add_menu_item")->name("add_menu_item");
	Route::get("edititem/{id}","ItemController@edititem")->name("edititem");
	Route::post("update_menu_item","ItemController@update_menu_item")->name("update_menu_item");
	Route::get("getitem/{id}","ItemController@getitem")->name("getitem");
	Route::get("deleteitem/{id}","ItemController@delete");

	Route::get("contactusls","CityController@contactusls");
	Route::get("contactdatatable","CityController@contactdatatable");

	//menu ingredients
	Route::get("menuingredients","IngredientsController@index");
	Route::get("ingredientsdatatable","IngredientsController@ingredientsdatatable")->name('ingredientsdatatable');
	Route::post("add_menu_ingre","IngredientsController@add_menu_ingre")->name("add_menu_ingre");
	Route::get("editing/{id}","IngredientsController@editing")->name("editing");
	route::post("update_menu_ingre","IngredientsController@update_menu_ingre")->name("update_menu_ingre");
	Route::get("deleteinge/{id}","IngredientsController@delete");


	//user
	Route::get("users","AuthenticationController@showuser");
	Route::get("userdatatable","AuthenticationController@userdatatable")->name("userdatatable");
	Route::get("deleteuser/{id}","AuthenticationController@deleteuser");

	//deliveryboy
	Route::get("deliveryboys","DeliveryController@index");
	Route::get("deliverydatatable","DeliveryController@deliverydatatable")->name("deliverydatatable");
	Route::post("add_delivery_boy","DeliveryController@add_delivery_boy")->name("add_delivery_boy");
	Route::get("deleteboy/{id}","DeliveryController@delete");
	Route::get("editdeliveryboys/{id}","DeliveryController@editdeliveryboys");
	Route::post("update_delivery_boy","DeliveryController@update_delivery_boy");

	//city
	Route::get("city","CityController@showcity");
	Route::get("citydatatable","CityController@citydatatable");
	Route::post("add_city","CityController@addcity");
	Route::get("editcity/{id}","CityController@editcity");
	Route::post("update_city","CityController@update_city");
	Route::get("deletecity/{id}","CityController@delete");

	//notification
	Route::get("sendnotification","NotificationController@index");
	Route::get("notificationdatatable","NotificationController@notificationdatatable")->name("notificationdatatable");
	Route::post("add_notification","NotificationController@addnotification");

	Route::get("updatekey/{key}","NotificationController@updatekey")->name("updatekey");
	Route::post("updatekeydata","NotificationController@updatekeydata")->name("updatekeydata");

	Route::get("editprofile","AuthenticationController@editprofile")->name("editprofile");
	Route::post("updateprofile","AuthenticationController@updateprofile")->name("updateprofile");

	Route::get("changepassword","AuthenticationController@changepassword")->name("changepassword");
	Route::get("samepwd/{id}","AuthenticationController@check_password_same");
	Route::post("updatepassword","AuthenticationController@updatepassword");

	Route::get("orderdatatable","OrderController@orderdatatable");
	Route::get("deleteorder/{id}","OrderController@deleteorder")->name("deleteorder");
	Route::get("actionorder/{id}/{status}","OrderController@orderaction")->name("orderaction");
	Route::get("getorderinfo/{id}","OrderController@getorderinfo")->name("getorderinfo");

	Route::get("changecurreny/{val}","AuthenticationController@changecurreny");
	Route::post("assignorder","OrderController@assignorder");
	Route::get("readyforpickup/{id}/{status}","OrderController@readyforpickup");
	Route::get("waitingforpickup/{id}","OrderController@waitingforpickup");
	  Route::get("notification/{id}","OrderController@notification");

});

Route::group(['prefix' => 'deliveryboy'], function () {

	Route::get("/","DeliveryController@login");
	Route::post("postlogin","DeliveryController@postlogin");
   
    Route::group(['middleware' => ['deliveryboy']], function () {
	   Route::get('dashboard',"DeliveryController@dashboard");
	   Route::get("logout","DeliveryController@logout");

	   Route::get("editprofile","DeliveryController@editprofile")->name("editprofile");
	   Route::post("updateprofile","DeliveryController@updateprofile")->name("updateprofile");

	   Route::get("orderhistory","DeliveryController@orderhistory");
	   Route::get("orderhistorydatatable","DeliveryController@orderhistorydatatable");

	   Route::get("changepassword","DeliveryController@changepassword")->name("changepassword");
	   Route::get("samepwd/{id}","DeliveryController@check_password_same");
	   Route::post("updatepassword","DeliveryController@updatepassword");

	   Route::get("changeattendace/{status}","DeliveryController@changeattendace");
	   Route::get("orderdatatable","DeliveryController@orderdatatable");
	   Route::get("getorderinfo/{id}","OrderController@getorderinfo")->name("getorderinfo");
	   Route::get("outofdelivery/{id}","DeliveryController@outofdelivery");
	   Route::get("ordercomplete/{id}","DeliveryController@ordercomplete");
   });
});






