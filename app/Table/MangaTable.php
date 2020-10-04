<?php

namespace App\Table;

use Core\Table\Table;

class MangaTable extends Table
{
    /**
     * used in MangaController:index
     * get Manga between range
     * @param int $offset start
     * @param int $limit count of item
     * @return array|bool|mixed|\PDOStatement
     */
    public function index($offset = 0, $limit = 20)
    {
        return $this->query("
			SELECT *
			FROM mangas
			ORDER BY id DESC
			LIMIT $offset, $limit
		");
    }

    /**
     * used in HomeController:search
     * search all manga that contains the text in their title
     * @param $text
     * @return array|bool|mixed|\PDOStatement
     */
    public function searchByTitle($text)
    {
        return $this->query("SELECT * FROM mangas WHERE title LIKE '%$text%'");
    }

    /**
     * used in AnimeController:add
     * get all mangas id and title to select a manga in order to add an anime on it
     * @return array|bool|mixed|\PDOStatement
     */
    public function getMangaTitle()
    {
        return $this->query('SELECT DISTINCT id, title FROM mangas ORDER BY title');
    }

    /**
     * used in HomeController:top
     * get list of top manga
     * @return array of Manga for Top
     */
    public function getMangaTop()
    {
        // id list of Top Manga
        $id_list = [1, 3, 4, 6, 9, 11, 13, 14, 20, 37, 36, 34, 33, 32, 31, 43, 40, 39, 38, 55, 53, 62, 61,
            58, 71, 70, 69, 67, 81, 79, 78, 77, 76, 86, 85, 84, 98, 97, 92, 104, 121, 134, 128, 142, 138,
            154, 153, 152, 148, 147, 161, 164, 216, 215];

        // init mangas list
        $mangas = [];

        // add stat of each mangas
        foreach ($id_list as $id) {
            array_push($mangas,$this->statManga($id));
                //$mangas = $this->statManga($id);
        }

        // return Top Manga list
        return $mangas;
    }

    /**
     * used in MangaTable:getMangaTop
     * return id, title, count of saison, total number of episodes for a Manga and the date of end
     * @param $id
     * @return array|bool|mixed|\PDOStatement
     */
    public function statManga($id) {
        return $this->query("
			SELECT mangas.id, title, COUNT(saison) as total_saison, SUM(nb_episodes) as total_episodes, end_date
			FROM mangas
			LEFT JOIN animes ON mangas.id = animes.id_manga_ref 
			WHERE mangas.id = ?
		", [$id], true);
    }

    /**
     * used in HomeController:stat
     * return id, title, count of saison, total number of episodes for a Manga and the date of end
     * @param $id
     * @return array|bool|mixed|\PDOStatement
     */
    public function stat() {
        return $this->query('
			SELECT COUNT(saison) as total_saison, SUM(nb_episodes) as total_episodes, SUM(nb_oav) as total_oav, SUM(nb_film) as total_film
			FROM mangas
			LEFT JOIN animes ON mangas.id = animes.id_manga_ref');
    }
}