<?php
session_start();

if (PHP_SAPI == 'cli-server') {
  // To help the built-in PHP dev server, check if the request was actually for
  // something which should probably be served as a static file
  $url  = parse_url($_SERVER['REQUEST_URI']);
  $file = __DIR__ . $url['path'];
  if (is_file($file)) {
    return false;
  }
}

use Respect\Validation\Validator as V;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/pagination.php';

// Instatiate app
$settings = require __DIR__ . '/../config/app.php';
$app = new \Slim\App($settings);

$app->add(new \App\Middleware\Cors());

$container = $app->getContainer();

// Error Handler
$container['errorHandler'] = function ($container) {
    return new \App\Exceptions\ErrorHandler($container['settings']['displayErrorDetails']);
};

// App Service Providers
$container->register(new \App\Services\Database\EloquentServiceProvider());
$container->register(new \App\Services\Auth\AuthServiceProvider());

// monolog
$container['logger'] = function ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    return $logger;
};

$container['image'] = function ($container) {
	$manager = new Intervention\Image\ImageManager();
	
	return $manager;
};

$container['RegisterController'] = function ($container) 
{
	return new \App\Controllers\Auth\RegisterController($container);
};

$container['LoginController'] = function ($container) 
{
	return new \App\Controllers\Auth\LoginController($container);
};

$container['UserController'] = function ($container)
{
	return new \App\Controllers\User\UserController($container);
};

$container['ProfileController'] = function ($container) {
	return new \App\Controllers\User\ProfileController($container);
};

$container['SearchController'] = function ($container) {
	return new \App\Controllers\Search\SearchController($container);
};

$container['UploadController'] = function ($container) {
	return new \App\Controllers\Upload\UploadController($container);
};

$container['fractal'] = function ()
{
	$manager = new League\Fractal\Manager;
	$manager->setSerializer(new League\Fractal\Serializer\ArraySerializer());
	return $manager;
};

$container['jwt'] = function ($container)
{
	return new Tuupola\Middleware\JwtAuthentication($container['settings']['jwt']);
};

$container['optionalAuth'] = function ($container)
{
	return new App\Middleware\OptionalAuth($container);
};

$container['validator'] = function ($container) {
	return new \App\Validation\Validator;
};

V::with('App\\Validation\\Rules\\');

require __DIR__ . '/../routes/api.php';
