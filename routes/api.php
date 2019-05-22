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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('save-surveyor','SettingsController@saveSurveyor');

Route::post('delete-surveyor','SettingsController@deleteSurveyor');

Route::post('enable-surveyor','SettingsController@enableSurveyor');

Route::get('pull-surveytypes','TabletController@pullSurveyTypes');

Route::post('save-surveytype','SettingsController@saveSurveyType');

Route::post('delete-surveytype','SettingsController@deleteSurveyType');

Route::post('enable-surveytype','SettingsController@enableSurveyType');

Route::get('pull-labs','TabletController@pullLabs');

Route::post('save-lab','SettingsController@saveLab');

Route::post('delete-lab','SettingsController@deleteLab');

Route::post('enable-lab','SettingsController@enableLab');

Route::get('pull-rooms','TabletController@pullRooms');

Route::post('save-room','SettingsController@saveRoom');

Route::post('delete-room','SettingsController@deleteRoom');

Route::post('enable-room','SettingsController@enableRoom');

Route::get('pull-floors','TabletController@pullFloors');

Route::post('save-room','SettingsController@saveRoom');

Route::post('delete-room','SettingsController@deleteRoom');

Route::post('enable-room','SettingsController@enableRoom');

Route::get('pull-products','TabletController@pullProducts');

Route::post('save-product','SettingsController@saveProduct');

Route::post('delete-product','SettingsController@deleteProduct');

Route::post('enable-product','SettingsController@enableProduct');

Route::get('pull-extents','TabletController@pullExtents');

Route::post('save-extent','SettingsController@saveExtent');

Route::post('delete-extent','SettingsController@deleteExtent');

Route::post('enable-extent','SettingsController@enableExtent');

Route::get('pull-surface-treatments','TabletController@pullSurfaceTreatments');

Route::post('save-surface-treatment','SettingsController@saveSurfaceTreatment');

Route::post('delete-surface-treatment','SettingsController@deleteSurfaceTreatment');

Route::post('enable-surface-treatment','SettingsController@enableSurfaceTreatment');

Route::post('pull-job','TabletController@pullJobInformation');

Route::post('pull-inspections','TabletController@pullInspections');

Route::post('push-job','TabletController@pushJob');

Route::post('push-inspections','TabletController@pushInspections');

Route::post('save-recommendation','AssessmentsController@saveRecommendation');

Route::post('save-comment','AssessmentsController@saveComment');

Route::post('delete-recommendation','AssessmentsController@deleteRecommendation');

Route::post('delete-comment','AssessmentsController@deleteComment');