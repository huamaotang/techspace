<?php
use Illuminate\Database\Capsule\Manager;

require __DIR__ . '/../vendor/autoload.php';

$app = new Illuminate\Container\Container;
with(new Illuminate\Events\EventServiceProvider($app))->register();
with(new Illuminate\Routing\RoutingServiceProvider($app))->register();

$manage = new Manager();
$manage->addConnection(require '../config/database.php');
$manage->bootEloquent();

require __DIR__ . '/../app/Http/routes.php';

$request = Illuminate\Http\Request::createFromGlobals();

$response = $app['router']->dispatch($request);

$response->send();

