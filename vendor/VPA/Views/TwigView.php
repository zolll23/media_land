<?php

namespace VPA\Views;

use \VPA\Log as Log;

class TwigView extends \VPA\View
{
    private $loader;
    private $twig;
    private $config;

    function __construct($config)
    {
	$this->config = $config;
	$this->loader = new \Twig\Loader\FilesystemLoader($config->getTplDir());
	$this->twig = new \Twig\Environment($this->loader, [
	    'cache' => $config->useCache() ? $config->getTplCacheDir() : false,
	    'debug' => true,
	]);
	$this->twig->addExtension(new \Twig\Extension\DebugExtension());
    }

    function loadTemplate(string $file)
    {
	$this->twig->addGlobal('session', $_SESSION ?? []);
	$log = Log::getInstance();
	$this->twig->addGlobal('sql', count($log->get('mysql')) );
	return $this->twig->load($file);
    }
}