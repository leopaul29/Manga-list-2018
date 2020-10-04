<?php

namespace App\Controller;

use \Core\Controller\PaginationController;

class MangaController extends AppController
{
    /**
     * MangaController constructor.
     * init Table for Manga and Anime
     * @param $page
     */
    public function __construct($page)
    {
        parent::__construct($page);

        $this->loadModel('Manga');
        $this->loadModel('Anime');
    }

    /**
     * list all manga info
     * 9 per page
     */
    public function index()
    {
        // init pagination parameters
        $offset = 0;
        $limit = 9;
        if (isset($_GET['page'])) {
            $offset = ($_GET['page'] - 1) * $limit;
        }

        // init pagination controller
        $pagination = PaginationController::paginate($this->Manga->count(), $offset, $limit);

        // get manga start offset
        $mangas = $this->Manga->index($offset, $limit);

        // set data and render for twig
        $datas = compact('mangas', 'pagination');
        $this->render('manga/index.twig', $datas);
    }

    /**
     * show info of one manga
     */
    public function show()
    {
        // get manga or redirect to notFound
        $manga = $this->Manga->find($_GET['id']);
        if (!$manga) {
            $this->notFound();
        }

        // get all saison of this manga
        $animes = $this->Anime->animeByMangaRef($_GET['id']);

        // set data and render for twig
        $datas = compact('manga', 'animes');
        $this->render('manga/show.twig', $datas);
    }

    /**
     * add form
     * @param array $param error/success
     */
    public function add($param = [])
    {
        // set data and render for twig
        $this->render('manga/add.twig', $param);
    }

    /**
     *
     */
    public function create()
    {
        // init tab alert
        $create_manga = [];
        $create_anime = [];

        // create manga
        if (!empty($_POST['title'])) {

            $req = $this->Manga->create([
                'title' => trim($_POST['title']),
                'description' => $_POST['description']
            ]);
            // get last insert id manga
            $lastInsertIdManga = $this->Manga->lastInsertId();
            // alert info manga
            $create_manga = ['res' => $req, 'manga' => $this->Manga->find($lastInsertIdManga)];

            // manga creation successful then create Anime
            if ($req) {
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
                    'id_manga_ref' => $lastInsertIdManga,
                    'title_original' => $title_original,
                    'saison' => $_POST['saison'],
                    'nb_episodes' => $_POST['nb_episodes'],
                    'nb_film' => $_POST['nb_film'],
                    'nb_oav' => $_POST['nb_oav'],
                    'status' => $_POST['status'],
                    'start_date' => $start_date,
                    'end_date' => $end_date
                ]);
                // get last insert id anime
                $lastInsertIdAnime = $this->Anime->lastInsertId();
                // alert info anime
                $create_anime = ['res' => $req, 'anime' => $this->Anime->find($lastInsertIdAnime)];
            }
        }

        // redirect to add form with alert info
        $this->add(compact('create_manga', 'create_anime'));
    }

    /**
     * search function
     */
    public function search()
    {
        if (!empty($_GET['search'])) {
            $mangas = $this->Manga->searchByTitle($_GET['search']);

            // if no manga found, redirect to 404
            if (empty($mangas)) {
                $this->notFound();
            } // if many mangas, display all like manga index
            else if (sizeof($mangas) > 1) {
                $datas = compact('mangas');
                $this->render('manga/index.twig', $datas);
                return;
            } // else go directly to the view of the manga found
            else {
                $this->redirection("index.php?p=manga.show&id=" . $mangas[0]->id);
                return;
            }
        } else {
            $this->index();
            return;
        }
    }

}