<?php
// route '/' and component Home.vue 

use App\LicenceModel;
use App\Queues;
use App\SettingsApp;

require 'routes/HomeRoute.php';


Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('test', function () {
	return	Lang::get('aboutMe');
});
