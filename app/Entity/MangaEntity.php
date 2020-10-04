<?php

namespace App\Entity;

use Core\Entity\Entity;

class MangaEntity extends Entity {

    public function getExtrait() {
        return substr($this->description, 0, 200) . '...';
    }

    public function getUrl() {
        return 'index.php?p=manga.show&id=' . $this->id;
    }

    function getImage() {
        return "images/icons/" . str_replace([' ', ':', '/'], '_', strtolower($this->title) ) . ".jpg";
    }

}