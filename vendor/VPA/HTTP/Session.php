<?php

namespace VPA\HTTP;

class Session
{
    static function init():bool
    {
	return session_start();
    }

    static function set(string $name,$value)
    {
	Session::init();
	$_SESSION[$name] = $value;
	return $_SESSION[$name];
    }

    static function get(string $name)
    {
	Session::init();
	return array_key_exists($name,$_SESSION) ? $_SESSION[$name] : false;
    }
}