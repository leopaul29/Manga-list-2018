<?php

namespace App\Controller;

use \App;
use App\Views\Templates\Extensions\MangaExtension;
use Core\Controller\Controller;
use \Core\Auth\DBAuth;

class AppController extends Controller {

    /**
     * @var string path for twig view files
     */
	protected $viewPath = '/app/Views/templates';
    /**
     * @var string name of twig page for notFound method
     */
	protected $notFound = '404.twig';

    /**
     * AppController constructor.
     * set current_page property for twig view
     * add MangaExtension
     * @param $page
     */
	public function __construct($page) {
        parent::__construct();
		
		$app = App::getInstance();
		$auth = new DBAuth($app->getDB());
		
		$this->addGlobal('current_page', $page);

		// add personal extension
        $this->twig->addExtension(new MangaExtension());
	}

    /**
     * load Table
     * @param $model_name
     */
	protected function loadModel($model_name) {
		$this->$model_name = App::getInstance()->getTable($model_name);
	}

    /**
     * redirect to url
     * @param $url
     */
	protected function redirection($url) {
		header('Location: ' . $url);
	}

}