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

Route::view('/', 'home');

Route::view('/report', 'report');
Route::post('/report', 'HomeController@ReportIssue');

Route::post('/process', 'GwikiController@Process');

Route::post('/mailing-list/subscribe', 'HomeController@MailingListSubscriber');

Route::post('/log/page-view', 'HomeController@LogPageView');
Route::post('/log/usage', 'HomeController@LogFingerprint');
