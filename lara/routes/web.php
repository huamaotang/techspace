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
    return view('welcome');
});

Route::get('users', function () {
	$res = DB::insert('insert into users (name, email, password) values (?, ?, ?)', array('Dayle', 'xx', 'xx'));
	var_dump($res);

	$results = DB::select('select * from users');
	var_dump($results);

	$uRes = DB::update('update users set email = ? where name = ?', array('xx@qq.com', 'tom'));
	var_dump($uRes);

	return View::make('users')->with('users', $results);
});

Route::get('trans', function () {
	DB::transaction(function()
	{
		DB::table('users')->update(array('votes' => 1));

		DB::table('posts')->delete();
	});
});

Route::get('logs', function () {
	$results = DB::select('select * from users');
	$queries = DB::getQueryLog();
	var_dump($results, $queries);
});

Route::get('test', function () {
	$data = DB::table('users')->get();
	var_dump($data);die;

	return view('test', [
		'id' => 1,
		'arr' => [
			['a', 'b', 'c']
		]
	]);
});




