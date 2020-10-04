<?php

namespace App\Controller;

use \Core\Controller\PaginationController;

class AnimeController extends AppController
{

    public function __construct($page)
    {
        parent::__construct($page);

        $this->loadModel('Anime');
        $this->loadModel('Manga');
    }


    public function index()
    {
        $offset = 0;
        $limit = 8;
        if (isset($_GET['page'])) {
            $offset = ($_GET['page'] - 1) * $limit;
        }

        $pagination = PaginationController::paginate($this->Anime->count(), $offset, $limit);

        $animes = $this->Anime->index($offset, $limit);

        $datas = compact('animes', 'pagination');
        $this->render('anime/index.twig', $datas);
    }

    public function finished()
    {
        $this->indexByStatus(1);
    }

    public function inprogress()
    {
        $this->indexByStatus(2, true);
    }

    public function currentSaison()
    {
        $this->indexByStatus(3, true);
    }

    public function mustsee()
    {
        $this->indexByStatus(4);
    }

    public function onbreak()
    {
        $this->indexByStatus(5);
    }

    public function add($param = [])
    {
        $mangas = $this->Manga->getMangaTitle();

        $data = compact('mangas', 'param');
        $this->render('anime/add.twig', $data);
    }

    public function create()
    {
        $create_anime = [];
        if (!empty($_POST['id_manga_ref'])) {

            $start_date = date("Y-m-d H:i:s", time());
            $end_date = null;
            if ($_POST['status'] == '1') {
                $end_date = date("Y-m-d H:i:s", time() + 1);
            }

            $title_original = null;
            if (!empty($_POST['title_original'])) {
                $title_original = $_POST['title_original'];
            }

            $req = $this->Anime->create([
                'id_manga_ref' => $_POST['id_manga_ref'],
                'title_original' => $title_original,
                'saison' => $_POST['saison'],
                'nb_episodes' => $_POST['nb_episodes'],
                'nb_film' => $_POST['nb_film'],
                'nb_oav' => $_POST['nb_oav'],
                'status' => $_POST['status'],
                'start_date' => $start_date,
                'end_date' => $end_date

            ]);

            $lastInsertIdAnime = $this->Anime->lastInsertId();

            $create_anime = ['res' => $req, 'anime' => $this->Anime->find($lastInsertIdAnime)];
        }

        $this->add(compact('create_anime'));
    }

    public function update()
    {
        // check if variable field isset and if its one of the field updatable
        if (isset($_GET['field']) && in_array($_GET['field'], ['nb_episodes', 'nb_film', 'nb_oav', 'status'])) {
            $fieldToUpdate = $_GET['field'];

            $this->Anime->update($_GET['id'],
                [
                    $fieldToUpdate => $_POST['value']
                ]
            );

            echo $this->Anime->find($_GET['id'])->$fieldToUpdate;
        } else {
            echo 'Update Failed';
        }
    }

    public function updateStatus()
    {
        // check if variable field isset and if its one of the field updatable
        if (isset($_GET['status'])) {
            $value = $_GET['status'];

            $this->Anime->update($_GET['id'],
                [
                    'status' => $value
                ]
            );

            echo $this->Anime->find($_GET['id'])->status;
        } else {
            echo 'Update Failed';
        }

        $this->redirection("index.php?p=manga.show&id=" . $this->Anime->find($_GET['id'])->id_manga_ref);
    }

    public function finish()
    {
        if (!empty($_GET['id'])) {
            $this->Anime->update($_GET['id'],
                [
                    'status' => '1',
                    'end_date' => date("Y-m-d H:i:s", time())
                ]
            );

            echo $this->Anime->find($_GET['id'])->status;
        } else {
            echo 'Update Failed';
        }
    }

    private function indexByStatus($status, $isEditable = false)
    {
        $offset = 0;
        $limit = 8;
        if (isset($_GET['page'])) {
            $offset = ($_GET['page'] - 1) * $limit;
        }

        $pagination = PaginationController::paginate($this->Anime->countByStatus($status), $offset, $limit);

        $animes = $this->Anime->indexByStatus($offset, $limit, $status);

        $datas = compact('animes', 'pagination', 'status', 'isEditable');
        $this->render('anime/index.twig', $datas);
    }

    /*public function category()
    {
        $mangas = $this->Manga->find($_GET['id']);
        if ($mangas === false) {
            $this->notFound();
        }

        $animes = $this->Anime->lastByCategory($_GET['id']);
        $mangas = $this->Manga->categoriesCountArticles();

        $datas = compact('category', 'animes', 'mangas');
        $this->render('articles/category.twig', $datas);
    }

    public function show()
    {
        $anime = $this->Anime->findWithCategory($_GET['id']);
        if ($anime == false) {
            $this->notFound();
        }

        $datas = compact('anime');
        $this->render('articles/show.twig', $datas);
    }*/
}