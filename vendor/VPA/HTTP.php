<?php

namespace VPA;

class HTTP
{
    private $method;
    private $uri;
    private $params;

    function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
	$this->params = $this->getHTTPdata();
    }

    /**
    * Returns current HTTP method
    **/
    public function getMethod():string
    {
	return $this->method;
    }

    /**
    * Returns current HTTP URI
    **/
    public function getURI():string
    {
	return $this->uri;
    }

    public function getParams():array
    {
	if ($this->params===null) {
	    $this->getHTTPdata();
	}
	return $this->params;
    }

    private function getHTTPdata()
    {
        switch ($this->method) {
            case 'GET':
                $this->params = $this->getGETdata();
            break;
            case 'POST':
                $this->params = $this->getPOSTdata();
            break;
            case 'PUT':
                $this->params = $this->getPOSTdata();
            break;
            default:
                $this->params = [];
            break;
            }
    }

    private function getGETdata():array
    {
	return $_GET;
    }

    private function getPOSTdata():array
    {
	return $_POST;
    }

    private function getPUTdata():array
    {
        $params = json_decode($this->loadPUT(),true);
        // we wait a correct JSON string
	if ($params===NULL) {
	    throw new Exception("PUT JSON format incorrect");
	}
	return $params;
    }

    private function loadPUT()
    {
        $fd = fopen("php://input","r");
        $str = fread($fd,1000);
        fclose($fd);
        return $str;
    }


    /**
    * Redirect to URL
    **/
    public function redirectTo(string $url):bool
    {
	header(sprintf('Location:%s',$url));
    }

    /**
    * Output HTTP header for 404 error
    **/
    public function pageNotFound()
    {
	header ('HTTP/1.1 404 Not Found');
    }

    public function contentType($type)
    {
	switch ($type) {
	    case 'json':
		$header = 'Content-Type: application/json';
	    break;
	    default:
		$header = 'Content-Type: text/html; charset=UTF-8';
	}
	header ($header);
    }

}