<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/validate.php');

function abnormalFin()
{
    header("Location: index.php");
    $_SESSION['error'] = true;
    exit();
}

if (!isset($_SESSION['userid'])) {abnormalFin();}
if ($_SERVER["REQUEST_METHOD"] !== "POST" || !isset($_FILES["file"]) || $_FILES["file"]["error"] !== UPLOAD_ERR_OK) abnormalFin();
$target_file = __DIR__ . "/upload/" . basename($_FILES["file"]["name"]);

if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) abnormalFin();

$filmModel = new \Webdev\Filmforge\FilmModel(new \Webdev\Filmforge\GenericQuery());
$file = fopen($target_file, 'r');

if (!$file) abnormalFin();

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

        $filmModel->addFilm($film, $_SESSION['userid']);
        $i = 1;
        $film = [];
    }

}
fclose($file);
unlink($target_file);

header("Location: index.php");
