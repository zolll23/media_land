<?php

namespace VPA;

/**
* Provide simple validation methods
**/


class SimpleValidator
{
    /**
    * Dirty data from a dangerous internet
    **/
    protected $dirty_params;
    /**
    * Clean data
    **/
    protected $clean_params = [];
    protected $validation_errors = [];

    /**
    * Get all form params as array ['fieldname1'=>'value1','fieldname2'=>'value2',...]
    * string|false cleanMethod - group cleaning method for all values
    **/
    function __construct ($dirty_params,$cleanMethod='Dummy')
    {
	$this->dirty_params = $dirty_params;
	if ($cleanMethod) {
	    foreach ($this->dirty_params as $name => $value) {
		switch ($cleanMethod) {
		    default:
			$this->cleanDummy($name);
		    break;
		}
	    }
	}
    }

    public function getCleanData():array
    {
	return $this->clean_params;
    }

    /**
    * Return validations errors
    **/
    public function getErrors():array
    {
	return $this->validation_errors;
    }

    function isEmail($name):bool
    {
	$value = $this->cleanDummy($name);
	if (empty($value)) {
	    $this->setValidationError($name,'empty','Empty value');
	    return false;
	}

	// very simple check for email
	if (strpos($value,'@')===false) {
	    $this->setValidationError($name,'not_email','Incorrect Email');
	    return false;
	}
	return true;
    }

    function isNotEmpty($name):bool
    {
	$value = $this->cleanDummy($name);
	if (empty($value)) {
	    $this->setValidationError($name,'empty','Empty value');
	    return false;
	}
	return true;
    }

    function isEqual($name1,$name2):bool
    {
	$value1 = $this->cleanDummy($name1);
	$value2 = $this->cleanDummy($name2);
	if ($value1!=$value2) {
	    $this->setValidationError($name2,'not_equal','Values not equal');
	    return false;
	}
	return true;
    }

    /**
    * Nothing do
    **/
    private function cleanDummy($name):string
    {
	$this->clean_params[$name] = $this->dirty_params[$name];
	return $this->clean_params[$name];
    }


    public function setValidationError($name,$code,$text)
    {
	$this->validation_errors[$name]=['code'=>$code,'message'=>$text];
    }
}