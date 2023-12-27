<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');

if (isset($_SESSION['userid']))
{$_SESSION['rez'] = 'anonymous user can not add movies, please return and login <br>';}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);

        //TODO change to Dependendency injection
        $filmModel = new \Webdev\Filmforge\FilmModel(new \Webdev\Filmforge\GenericQuery());
        $filmModel->butchFilmAddingFromTextFile($_FILES["file"]["tmp_name"]);
        header("Location: index.php");
    }
}