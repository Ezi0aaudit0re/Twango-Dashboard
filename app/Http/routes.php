<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Blade::setContentTags('<%', '%>');        // for variables and all things Blade
Blade::setEscapedContentTags('<%%', '%%>');   // for escaped data


Route::group(['prefix' => 'api/v1/admin'], function(){
    Route::get('/allUsers', 'UserController@getUsers');
    Route::get('/singleUser/{id}', 'UserController@singleUser');
    Route::get('/profilePhotos/{id}', 'ProfilePhotoController@getProfilePhotos');
    Route::get('/updatePhotoScore/{id}/{score}', 'UserController@updatePhotoScore');
    Route::get('/updateCollegeScore/{id}/{score}', 'UserController@updateCollegeScore');
    Route::get('getChat/{aId}/{bId}','UserController@getChat');
    Route::get('allChats', 'UserController@getAllChats');
    Route::get('getChatInfo/{Aid}/{Bid}', 'UserController@getChatInfo');
    Route::get('/sortAllData/{orderBy}/{order}', 'UserController@getUsers');
    Route::get('/getCities', 'UserController@getCities');
    Route::get('getNames', 'UserController@getNames');
    Route::get('getSearchedData', 'UserController@getSearchedData');
    Route::get('getStatus', 'UserController@getStatus');

    Route::get('disapprovedUsers','AdminUserController@getDisapprovedUsers');
    Route::get('/unapprovedUsers', 'AdminUserController@getDisapprovedUsers');
    Route::put('disapproveuser','AdminUserController@disapproveUser');
    Route::get('blockedUsers','AdminUserController@getBlockedUsers');
    Route::put('blockuser','AdminUserController@blockUser');
    Route::put('approveuser','AdminUserController@approveUser');
    Route::get('getWaitlistedUsers','AdminUserController@getWaitlistedUsers');
    Route::get('getInvitedWaitlistedUsers','AdminUserController@getInvitedWaitlistedUsers');
    Route::put('waitlistUser','AdminUserController@waitlistUser');
    Route::get('unverifiedUsers','AdminUserController@getUnverifiedUsers');
    Route::put('verifyuser/{id}','AdminUserController@verifyUser');
    Route::put('unverifyuser','AdminUserController@unverifyUser');
    Route::get('getAdmins','AdminUserController@getAdmins');
    Route::get('reportedUsers','AdminUserController@getReportedUsers');
    Route::put('removeuserfromreported','AdminUserController@removeuserfromreported');
    Route::get('rejectedUsers','AdminUserController@getRejectedUsers');
    Route::get('users','AdminUserController@getAll');
    Route::put('approveAdmin/{id}','AdminUserController@approveAdmin');
    Route::get('users/{userId}', 'AdminUserController@show');
    Route::get('/allColleges', 'UserController@getColleges');

    /* --- Routes for ratios ---*/
    Route::get('getMaleFemaleRatio','AdminRatioController@getMaleFemaleRatio');
    Route::get('getNewMaleFemaleRatio','AdminRatioController@getNewMaleFemaleRatio');
    Route::get('getActiveMaleFemaleRatio','AdminRatioController@getActiveMaleFemaleRatio');
    Route::get('getMutualLikeRatio','AdminRatioController@getMutualLikeRatio');
    Route::get('getavTimeFirstMatchRatio','AdminRatioController@getavTimeFirstMatchRatio');
    Route::get('getavTimeMatchRatio','AdminRatioController@getavTimeMatchRatio');

    /* -- Routes for notifications ---*/
    Route::get('allNotifications', 'NotificationController@getData');
    Route::post('addNotification', 'NotificationController@add');
    Route::get('deleteNotification', 'NotificationController@delete');
    Route::put('editNotification', 'NotificationController@edit');

});

Route::group(['prefix' => 'api/v2/admin'], function(){
    Route::get('activity', 'UserController@getActivityData');
    Route::put('makeProfilePicture', 'ProfilePhotoController@makeProfilePicture');
    Route::get('deletePhoto', 'ProfilePhotoController@deletePhoto');
    Route::get('saveImage', 'ProfilePhotoController@saveImage');
    Route::put('saveFilteredImage', 'ProfilePhotoController@addFilteredImage');
    Route::put('matchAssign', 'AdminUserController@matchAssignNew');
    Route::get('stack', 'UserController@getStackData');
    Route::get('getMatchAssign', 'AdminUserController@getMatchAssign');
});

Route::get('/all', function(){
    return view('main');
});
// Route::get('/purchasedValue/{value?}', 'UserController@conditions');


// Route::auth();
//
// Route::get('/home', 'HomeController@index');
