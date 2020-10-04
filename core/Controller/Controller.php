<?php

namespace Core\Controller;

use Core\Twig\Extensions\CoreExtension;

use \Twig_Loader_Filesystem;
use \Twig_Environment;
use \Twig_Extension_Debug;

class Controller {

	protected $twig;
	protected $viewPath;

	protected $notFound = '_404.twig';
	protected $forbidden = '_403.twig';

	public function __construct() {
		// init Twig_Loader_Filesystem with Core tempates
		$loader = new Twig_Loader_Filesystem(ROOT . '/core/twig');
		// add App templates
		$loader->addPath(ROOT . $this->viewPath);

		// configure twig
		$this->twig = new Twig_Environment($loader, [
		    'cache' => false,
		    'debug' => true
		]);
		// add DebugExtension
		$this->twig->addExtension(new Twig_Extension_Debug());
		// add CoreExtension
		$this->twig->addExtension(new CoreExtension());
	}

	protected function render($view, $datas = []) {
		// display template twig
		echo $this->twig->render($view, $datas);
	}

	protected function addGlobal($key, $value) {
		// set variable global
		$this->twig->addGlobal($key, $value);
	}

	protected function forbidden() {
		header("HTTP/1.0 403 Forbidden");
		$this->render($this->forbidden);
		die();
	}

	protected function notFound() {
		header("HTTP/1.0 404 Not Found");
		$this->render($this->notFound);
		die();
	}

}