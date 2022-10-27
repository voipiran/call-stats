<?php

Route::get('/', 'HomeController@index');
// public function
Route::post('/allActions', 'AllFunctionController@actions');

// get language for page realTime
Route::get('/get-lang', 'AllFunctionController@getLanguage');

// home component
Route::post('/homeActions', 'HomeController@actions');
// answered component
Route::post('/answeredActions', 'AnsweredController@actions');
// unAnswered component
Route::post('/unAnsweredActions', 'UnAnsweredController@actions');
// distribution component
Route::post('/distributionActions', 'DistributionController@actions');
// Operator component
Route::post('/operatorActions', 'OperatorController@actions');
// search component
Route::post('/searchActions', 'SearchController@actions');
