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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

/*****************************************************************************/

// Surveys

Route::get('/home', 'HomeController@index');

Route::get('/users','UsersController@index');

Route::get('/user/{username}','UsersController@getUser');

Route::post('saveUser','UsersController@saveUser');

Route::get('/changepassword/{username}','UsersController@changePassword');

Route::post('savePassword','UsersController@savePassword');

Route::post('deleteUser','UsersController@deleteUser');

Route::get('/clients','ClientsController@index');

Route::get('/client/{client_name}','ClientsController@getClient');

Route::post('saveClient','ClientsController@saveClient');

Route::post('deleteClient','ClientsController@deleteClient');

Route::get('/surveys','SurveysController@index');

Route::get('/survey/{job_number}', 'SurveysController@getJob');

Route::post('saveJob','SurveysController@saveJob');

Route::post('exportJobInfo','SurveysController@exportJobInfo');

Route::get('/inspections/{ukas_number}', 'SurveysController@getFloors');

Route::get('/inspections/{ukas_number}/{floor}', 'SurveysController@getInspections');

Route::post('saveInspection', 'SurveysController@saveInspection');

Route::post('deleteInspection', 'SurveysController@deleteInspection');

Route::post('exportJobInspections','SurveysController@exportInspections');

Route::get('/import/{job_number}','SurveysController@showImport');

Route::get('/reports/{job_number}','ReportsController@showReports');

Route::post('importCSV','SurveysController@importCSV');

Route::post('uploadJobNumberFile','SurveysController@uploadFile');

Route::post('removeFile','SurveysController@removeFile');

Route::post('uploadJobNumberReport','ReportsController@uploadReport');

Route::post('removeReport','ReportsController@removeReport');

Route::post('printReport','PdfController@printReport');

Route::post('upload','TabletController@uploadDataFromTablet');

Route::get('/specs','RemovalsController@index');

Route::get('/spec/{spec}','RemovalsController@getRemoval');

Route::post('saveSpec', 'RemovalsController@saveRemoval');

Route::post('getAddress', 'RemovalsController@getSurveyAddress');

Route::post('/importRemovalPicture','RemovalsController@importPicture');

Route::post('/saveRemovalAreaTitle','RemovalsController@saveRemovalAreaTitle');

Route::post('/saveRemovalAreaText','RemovalsController@saveRemovalAreaText');

Route::post('/saveRemovalInspection','RemovalsController@saveRemovalInspection');

Route::post('/printRemovalPdf','RemovalsPdfController@printReport');

Route::get('/issues/{job_number}','SurveysController@showIssues');

Route::get('/issues/{ukasnumber}/{revision}','SurveysController@showIssue');

Route::post('/deleteSurveyReportRevision','SurveysController@deleteSurveyReportRevision');

Route::post('/saveSurveyReportRevision','SurveysController@saveSurveyReportRevision');

Route::get('/settings','SettingsController@index');

/*****************************************************************************/

// Fire Risk Assessment

Route::post('/getFra', 'AssessmentsController@getFra');

Route::post('/saveFra', 'AssessmentsController@saveFra');

Route::post('/saveShop', 'AssessmentsController@saveShop');

Route::post('/deleteShop','AssessmentsController@deleteShop');

Route::post('/importSignature','AssessmentsController@importSignature');

Route::post('/importReviewSignature','AssessmentsController@importReviewSignature');

Route::post('/importMainPicture','AssessmentsController@importMainPicture');

Route::post('/importPicture','AssessmentsController@importPicture');

Route::post('/printFraPdf','FrasController@printReport');

Route::post('/deleteRevision','AssessmentsController@deleteRevision');

Route::get('/shops', 'AssessmentsController@getShops');

Route::get('/shop/{shop_id}', 'AssessmentsController@getShop');

Route::get('/fire-risk-assessments', 'AssessmentsController@index');

Route::get('/fire-risk-assessment/{shop_id}/{revision}', 'AssessmentsController@loadFra');

Route::post('/addOtherPicture','AssessmentsController@addOtherPicture');

Route::post('/removeOtherPicture','AssessmentsController@removeOtherPicture');

Route::get('/rasettings','RaSettingsController@index');

Route::post('/saveSettings','RaSettingsController@saveSettings');

Route::post('/checkAnswer','AssessmentsController@checkAnswer');

Route::get('/recommendations-and-comments', function () {
    return redirect('/recommendations-and-comments/1');
});

Route::get('/recommendations-and-comments/{client_id}','AssessmentsController@getRecommendationsAndComments');
