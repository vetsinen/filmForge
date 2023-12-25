<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');

if (isset($_SESSION['userid']))
{echo 'anonymous user can not add movies, please return and login <br>';}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
//        echo "<h1>File Content:</h1>";
//        echo "<pre>" . htmlspecialchars($fileContent) . "</pre>";

        //TODO change to Dependendency injection
        $filmModel = new \Webdev\Filmforge\FilmModel(new \Webdev\Filmforge\MySQLConnection());
        $filmModel->butchFilmAddingFromTextFile($_FILES["file"]["tmp_name"]);
        header("Location: index.php");
    }
}