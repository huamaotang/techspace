<?php
$app['router']->get('/', function () {
	return 'hello world';
});

$app['router']->get('welcome', 'App\Http\Controllers\WelcomeController@index');

$app['router']->get('welcome/get', 'App\Http\Controllers\WelcomeController@get');