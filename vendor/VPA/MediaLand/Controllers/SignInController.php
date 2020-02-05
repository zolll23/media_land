<?php

namespace VPA\MediaLand\Controllers;

class SignInController extends \VPA\Controller
{
    /**
    * Implementation of our Home Page Controller
    **/
    static function run(\VPA\Config $config,\VPA\HTTP $http,array $uri_data)
    {
	$self =  __CLASS__;
	// Init Twig for our controller
	$view = new \VPA\Views\TwigView($config);
	$controller = new $self($config,$http,$uri_data);
	$controller->setView($view);
	$controller->showSignInPage();
    }

    static function signout(\VPA\Config $config,\VPA\HTTP $http,array $uri_data)
    {
	$user = \VPA\HTTP\Session::set('login',null);
	$http->redirectTo('/home');
	return true;
    }


    static function validate(\VPA\Config $config,\VPA\HTTP $http,array $uri_data)
    {
	$self =  __CLASS__;
	$controller = new $self($config,$http,$uri_data);
	$data = $controller->validateSignin($http);
	$view = new \VPA\Views\JSON($config);
	$template = $view->render($data);

	$http->contentType('json');
	echo $template;
    }

    public function validateSignin(\VPA\HTTP $http):array
    {
	$v = new \VPA\SimpleValidator($http->getParams());
	// Validate a form data
	if ($v->isEmail('email') && $v->isNotEmpty('password')) {

	    $rs = new \VPA\MediaLand\Models\Users();

	    // Get clean data for searching and find him
	    $data = $v->getCleanData();
	    $user = $rs->getAuthUser($data);

	    // User found, return OK
	    if (!empty($user)) {
		\VPA\HTTP\Session::set('login',$user);
		$answer = [
		    'status' => 'ok',
		    'values' => $data,
		];
	    } else {
		// User not found, output global form error message
		$v->setValidationError('_total','not_exist','User not found!');
		$answer = [
		    'status' => 'error',
		    'errors' =>  $v->getErrors(),
		    'values' =>  $v->getCleanData(),
		];
	    }
	} else {
	    // Validation is fail
	    $answer = [
		'status' => 'error',
		'errors' =>  $v->getErrors(),
		'values' =>  $v->getCleanData(),
	    ];
	}
	return $answer;
    }

    public function showSignInPage()
    {
	$user = \VPA\HTTP\Session::get('login');
	// User already singed
	if (!empty($user)) {
	    $this->http->redirectTo('/home');
	    return true;
	}
	$template = $this->view->loadTemplate('signin.html');
	echo $template->render([]);
    }


}