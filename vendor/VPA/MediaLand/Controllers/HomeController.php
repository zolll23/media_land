<?php

namespace VPA\MediaLand\Controllers;

class HomeController extends \VPA\Controller
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

	$controller->showHomePage();
    }

    public function showHomePage()
    {
	$user_sess = \VPA\HTTP\Session::get('login');
	$user_db = [];
	$role = '';
	if (!empty($user_sess)) {
	    $rs = new \VPA\MediaLand\Models\Users();
	    $user_db = $rs->getAuthUser($user_sess);

	    $role = 'Anonymous';
	    switch ($user_db['role']) {
		case 1:
		    $role = 'User';
		break;
		case 2:
		    $role = 'Administrator';
		break;
	    }
	}

	$template = $this->view->loadTemplate('home.html');
	echo $template->render(['logged' => !empty($user_db), 'user' => $user_db, 'role' => $role]);
    }

}