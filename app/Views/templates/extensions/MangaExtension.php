<?php

namespace App\Views\Templates\Extensions;

use \Twig_Extension;
use \Twig_SimpleFunction;

class MangaExtension extends Twig_Extension
{
    private static $status_labels = array(
        '1' => 'Finished',
        '2' => 'In progress',
        '3' => 'Current Saison',
        '4' => 'Must see',
        '5' => 'On break'
    );

    private static $status_colors = array(
        '1' => 'danger',
        '2' => 'success',
        '3' => 'info',
        '4' => 'secondary',
        '5' => 'warning'
    );

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('labelStatus', [$this, 'labelStatus']),
            new Twig_SimpleFunction('colorStatus', [$this, 'colorStatus']),
            new Twig_SimpleFunction('getStatusLabels', [$this, 'getStatusLabels']),
            new Twig_SimpleFunction('getStatusColors', [$this, 'getStatusColors'])
        ];
    }

    public function labelStatus($status)
    {
        return self::$status_labels[$status];
    }

    public function colorStatus($status)
    {
        return self::$status_colors[$status];
    }

    public function getStatusLabels() {
        return self::$status_labels;
    }

    public function getStatusColors() {
        return self::$status_colors;
    }
}