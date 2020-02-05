<?php

namespace VPA\MediaLand\Controllers;

class AccountController extends \VPA\Controller
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

	$controller->showAccountPage();
    }

    public function showAccountPage()
    {
	$user = \VPA\HTTP\Session::get('login');
	if (empty($user)) {
	    $this->http->redirectTo('/signin');
	    return true;
	}

	$role = 'Anonymous';
	$tpl = 'account_user.html';
	switch ($user['role']) {
	    case 1:
		$role = 'User';
		$tpl = 'account_user.html';
	    break;
	    case 2:
		$role = 'Administrator';
		$tpl = 'account_admin.html';
	    break;
	}
	$template = $this->view->loadTemplate($tpl);
	echo $template->render(['logged' => !empty($user), 'user' => $user, 'role' => $role]);
    }

}