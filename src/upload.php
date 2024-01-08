<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/validate.php');

if (isset($_SESSION['userid'])) {
    $_SESSION['rez'] = 'anonymous user can not add movies, please return and login <br>';
}
$target_dir = "/upload/";
$target_file = __DIR__.$target_dir . basename($_FILES["file"]["name"]);

if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) throw new Exception('cannot create file');

//chmod($target_file, 0777);

    error_log('uploaded');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {

        //TODO change to Dependendency injection
        $filmModel = new \Webdev\Filmforge\FilmModel(new \Webdev\Filmforge\GenericQuery());
        
        error_log($target_file);
        $file = fopen($target_file, 'r');

        if (!$file) {throw new \Exception("Unable to open the file.");}

        $i = 1;
        $film = [];
        while (!feof($file)) {
            $line = str_replace("\n", "", fgets($file));
            if ($i < 5) {
                $s = explode(":", $line, 2);
                if (sizeof($s) !== 2) break;
                $film[] = clearString($s[1]);
                $i++;
            } else {
                $film = [
                    'title' => $film[0],
                    'release_year' => $film[1],
                    'format' => $film[2],
                    'actors' => $film[3]
                ];

                $filmModel->addFilm($film);
                $i = 1;
                $film = [];
            }

        }
        fclose($file);

        //$filmModel->butchFilmAddingFromTextFile($_FILES["file"]["tmp_name"]);
        header("Location: index.php");
    }
}