<?php

namespace Core\Twig\Extensions;

use \Twig_Extension;
use \Twig_SimpleFunction;

class CoreExtension extends Twig_Extension {

	public function getFunctions() {
		return [
			new Twig_SimpleFunction('activeClass', [$this, 'activeClass'], ['needs_context'=>true])
		];
	}

	/**
	 * return active if the current_page name contains and start with page name ask
	 * @param $context content global variables
	 * @param $page page name to test
	 * @return String active class or nothing
	 */
	public function activeClass(array $context, $page) {
		if (isset($context['current_page']) && (strpos($context['current_page'], $page) === 0) ) {
			return ' active ';
		}
	}
}