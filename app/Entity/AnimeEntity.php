<?php

namespace App\Entity;

use Core\Entity\Entity;

class AnimeEntity extends Entity {

    public function getUrl() {
        return 'index.php?p=manga.show&id=' . $this->id_manga_ref;
    }

    function getImage() {
        return "images/icons/" . str_replace([' ', ':', '/'], '_', strtolower($this->title) ) . ".jpg";
    }
}