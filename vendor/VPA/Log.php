<?php

namespace VPA;

class Log
{
    private $log;

    function __construct()
    {
    }

    static function &getInstance()
    {
	static $instance;
        $class = __CLASS__;
	if (!isset($instance)) $instance = new $class;
	return $instance;
    }

    public function put(string $group,string $record)
    {
	$this->log[$group][]=$record;
    }

    public function get($group):array
    {
	return isset($this->log[$group]) ? $this->log[$group] : [];
    }
}