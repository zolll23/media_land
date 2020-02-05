<?php

namespace VPA\MediaLand\Controllers;

class SignUpController extends \VPA\Controller
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
	$controller->showSignUpPage();
    }

    static function validate(\VPA\Config $config,\VPA\HTTP $http,array $uri_data)
    {
	$self =  __CLASS__;
	$controller = new $self($config,$http,$uri_data);
	$data = $controller->validateSignup($http);
	$view = new \VPA\Views\JSON($config);
	$template = $view->render($data);

	$http->contentType('json');
	echo $template;
    }

    public function validateSignup(\VPA\HTTP $http):array
    {
	$v = new \VPA\SimpleValidator($http->getParams());
	// Validate a form data
	$rules = [
	    $v->isEmail('email'),
	    $v->isNotEmpty('password'),
	    $v->isEqual("password","password2"),
	    $v->isNotEmpty('birthday'),
	    $v->isNotEmpty('role'),
	    $v->isNotEmpty('country'),
	];

	// Succesful validations rules equals total amount validation rules
	if (array_sum($rules)==count($rules)) {

	    $rs = new \VPA\MediaLand\Models\Users();

	    // Get clean data for searching and find him
	    $data = $v->getCleanData();

	    // User found, return Error
	    if (!empty($user)) {
		$v->setValidationError('_total','exist','User already exist');
		$answer = [
		    'status' => 'error',
		    'errors' =>  $v->getErrors(),
		    'values' =>  $v->getCleanData(),
		];
	    } else {
    		$answer = [
		    'status' => 'ok',
		    'values' => $data,
		];

		$rs->addUser($data);

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

    public function showSignUpPage()
    {
	$user = \VPA\HTTP\Session::get('login');
	// User already signed
	if (!empty($user)) {
	    $this->http->redirectTo('/home');
	    return true;
	}
	$template = $this->view->loadTemplate('signup.html');
	echo $template->render([]);
    }

    static function showSucessfulPage(\VPA\Config $config,\VPA\HTTP $http,array $uri_data)
    {
	$view = new \VPA\Views\TwigView($config);
	$template = $view->loadTemplate('sucessful.html');
	echo $template->render([]);
    }


}