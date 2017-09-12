<?php
$app['router']->get('/', function () {
	return view('test', ['id' => 1]);
});

$app['router']->get('welcome', 'App\Http\Controllers\WelcomeController@index');

$app['router']->get('welcome/get/', 'App\Http\Controllers\WelcomeController@get');

$app['router']->any('welcome/{id}', function ($id) {
	return '$id=' . $id;
});

$app['router']->get('welcome/test', 'App\Http\Controllers\WelcomeController@test');



