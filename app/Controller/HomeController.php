<?php

namespace App\Controller;

use \App;
use Core\Controller\Controller;

class HomeController extends AppController
{

    public function __construct($page)
    {
        parent::__construct($page);

        $this->loadModel('Manga');
        $this->loadModel('Anime');
    }

    public function index()
    {
        /*
         * get 7 first current saison and in progress anime
         * merge the array
         * take the 7 first to display
         */
        $last_currentSaison = $this->Anime->indexByStatus(0, 7, 3);
        $last_onGoing = array_merge($last_currentSaison, $this->Anime->indexByStatus(0, 7, 2));
        $last_inProgress = array_slice($last_onGoing, 0, 7);

        $last_onBreak = $this->Anime->indexByStatus(0, 7, 5);
        $last_finish = $this->Anime->indexByStatus(0, 7, 1);
        $this->render('home/index.twig',
            compact('last_finish', 'last_inProgress', 'last_onBreak'));
    }

    public function top()
    {
        $mangas = $this->Manga->getMangaTop();
        //$mangas = $this->Manga->all();

        $data = compact('mangas');
        $this->render('home/top.twig', $data);
    }

    public function stat()
    {
        $statManga = $this->Manga->stat();

        $data = compact('statManga');
        $this->render('home/stat.twig', $data);
    }

    /**
     * rename images files
     */
    public function formatImages()
    {
        $dir = "images/icons/";
        $content = "Renamed images";

        // Ouvre un dossier bien connu, et liste tous les fichiers
        if (is_dir($dir)) {
            if ($dh = opendir($dir)) {
                // loop each file of this dir
                while (($file = readdir($dh)) !== false) {
                    if (is_file($dir . $file)) {
                        // init new name by replace all space, ':', '/' by underscore
                        $rename = str_replace([' ', ':', '/'], '_', strtolower($file));

                        if($file != $rename) {
                            // replace the name
                            $isRenamed = rename($dir . $file, $dir . $rename);
                            // if good, display the name => new name
                            if ($isRenamed) {
                                $content .= "fichier : $file => $rename <br>";
                            }
                        }
                    }
                }
                closedir($dh);
            }
        }

        // display content
        echo "<pre>$content</pre>";
    }

    /**
     * genere txt file for listmanga.txt
     */
    public function generateFileListManga()
    {
        $dir = "data/";
        $date = date("Y-m-d", time());
        $filename = $dir . 'list_manga_' . $date . '.txt';

        $content = "Generated the " . $date . "\r\n";

        // Current saison : status 3
        $content .= "========= This season =========\r\n";
        $last_currentSaison = $this->Anime->indexByStatus(0, 100, 3);
        foreach ($last_currentSaison as $anime) {
            $content .= "$anime->title : S$anime->saison => $anime->nb_episodes episodes vu\r\n";
        }
        $content .= "\r\n";

        // In Progress : status 2
        $content .= "========= In Progress =========\r\n";
        $last_inProgress = $this->Anime->indexByStatus(0, 100, 2);
        foreach ($last_inProgress as $anime) {
            $content .= "$anime->title : S$anime->saison => $anime->nb_episodes episodes vu\r\n";
        }
        $content .= "\r\n";

        // Must See : status 4
        $content .= "========= Must See =========\r\n";
        $last_mustSee = $this->Anime->indexByStatus(0, 100, 4);
        foreach ($last_mustSee as $anime) {
            $content .= "$anime->title : S$anime->saison => $anime->nb_episodes episodes vu\r\n";
        }
        $content .= "\r\n";

        // On Break : status 5
        $content .= "========= On Break =========\r\n";
        $last_onBreak = $this->Anime->indexByStatus(0, 100, 5);
        foreach ($last_onBreak as $anime) {
            $content .= "$anime->title : S$anime->saison => $anime->nb_episodes episodes vu\r\n";
        }
        $content .= "\r\n";

        // Finished : status 1
        $content .= "========= Finished =========\r\n";
        $last_finish = $this->Anime->indexByStatus(0, 300, 1);
        foreach ($last_finish as $anime) {
            $content .= "$anime->title : S$anime->saison => $anime->nb_episodes episodes vu\r\n";
        }
        $content .= "\r\n";

        // create, open & write content in file
        file_put_contents($filename, $content);

        // display content
        echo "<pre>$content</pre>";
    }

    /*
     * AnimList
Client ID : yrladd-d0zyb
Client Secret : rkrlX9V616zqH85tXty7i9Ii
     */
}