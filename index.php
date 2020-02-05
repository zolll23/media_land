<?php
require_once './vendor/autoload.php';


use VPA\HTTP\Router as Router;
use VPA\Config as Config;

try {

    $config = new Config('config.ini');

    $router = new Router($config);

    // Redirect from / to /home
    $router->add_route('GET','|^/$|i',function ($config,$http,$uri_data) {
	$http->redirectTo('/home');
    });

    // Open /home page
    $router->add_route('GET','|^/home$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\HomeController::run($config,$http,$uri_data);
    });

    // Open /account page
    $router->add_route('GET','|^/account$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\AccountController::run($config,$http,$uri_data);
    });

    /**************** Sign In Hanlders ***************/
    $router->add_route('GET','|^/signin$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\SignInController::run($config,$http,$uri_data);
    });

    $router->add_route('POST','|^/signin$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\SignInController::validate($config,$http,$uri_data);
    });

    $router->add_route('GET','|^/signout$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\SignInController::signout($config,$http,$uri_data);
    });

    /**************** Sign Up Hanlders ***************/
    $router->add_route('GET','|^/signup$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\SignUpController::run($config,$http,$uri_data);
    });

    $router->add_route('GET','|^/sucessful$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\SignUpController::showSucessfulPage($config,$http,$uri_data);
    });


    $router->add_route('POST','|^/signup$|i',function($config,$http,$uri_data) {
	VPA\MediaLand\Controllers\SignUpController::validate($config,$http,$uri_data);
    });

    // Handler for "page not found"
    $router->default_route(function($config,$http,$uri_data) {
	$http->pageNotFound();

	$view = new \VPA\Views\TwigView($config);
	$template = $view->loadTemplate('404.html');

	echo $template->render([]);
    });

    $router->route();


} catch (Exception $e) {
    echo "<hr>";
    echo $e;
    echo "<hr>";
}


