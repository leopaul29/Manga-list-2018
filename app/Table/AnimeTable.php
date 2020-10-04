<?php

namespace App\Table;

use Core\Table\Table;

class AnimeTable extends Table {

	public function index($offset = 0, $limit = 20) {
		return $this->query("
			SELECT animes.id, id_manga_ref, title, title_original, saison, nb_episodes, nb_film, nb_oav, status, start_date, end_date
			FROM animes
			LEFT JOIN mangas ON id_manga_ref = mangas.id
			ORDER BY animes.id DESC
			LIMIT $offset, $limit
		");
	}

    public function indexByStatus($offset = 0, $limit = 20, $status = 1) {
        return $this->query("
			SELECT animes.id, id_manga_ref, title, title_original, saison, nb_episodes, nb_film, nb_oav, status, start_date, end_date
			FROM animes
			LEFT JOIN mangas ON id_manga_ref = mangas.id
			WHERE status = $status
			ORDER BY animes.start_date DESC 
			LIMIT $offset, $limit
		");
    }

    public function countByStatus($status) {
        return (int) $this->query("
			SELECT count(*) as total
			FROM animes
			WHERE status = $status
		")[0]->total;
    }

    public function animeByMangaRef($id_manga_ref) {
        return $this->query("
			SELECT animes.id, id_manga_ref, title, title_original, saison, nb_episodes, nb_film, nb_oav, status, start_date, end_date
			FROM animes
			LEFT JOIN mangas ON id_manga_ref = mangas.id
			WHERE id_manga_ref = $id_manga_ref
			ORDER BY animes.end_date ASC
		");
    }

    /*public function admin_index($offset = 0, $limit = 100) {
		return $this->query("
			SELECT articles.id, articles.title, articles.create_at, articles.update_at, articles.category_id, categories.title as category
			FROM articles 
			LEFT JOIN categories ON category_id = categories.id
			ORDER BY articles.create_at DESC
			LIMIT $offset, $limit
		");
	}

	public function last($offset = 0, $limit = 100) {
		return $this->query("
			SELECT articles.id, articles.title, articles.content, categories.title as category
			FROM articles 
			LEFT JOIN categories ON category_id = categories.id
			ORDER BY articles.create_at DESC
			LIMIT $offset, $limit
		");
	}

	public function findWithCategory($id) {
		return $this->query("
			SELECT articles.id, articles.title, articles.content, categories.title as category, categories.id as category_id
			FROM articles 
			LEFT JOIN categories ON category_id = categories.id
			WHERE articles.id = ?
		", [$id], true);
	}

	public function lastByCategory($category_id) {
		return $this->query("
			SELECT articles.id, articles.title, articles.content, articles.create_at, articles.update_at, categories.title as category
			FROM articles
			LEFT JOIN categories ON category_id = categories.id
			WHERE category_id = ?
			ORDER BY articles.update_at DESC
		", [$category_id]);
	}*/
}