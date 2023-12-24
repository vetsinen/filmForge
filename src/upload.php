<?php
session_start();
if (isset($_SESSION['userid']))
{echo 'anonymous user can not add movies, please return and login <br>';}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
        $fileContent = file_get_contents($_FILES["file"]["tmp_name"]);
        echo "<h1>File Content:</h1>";
        echo "<pre>" . htmlspecialchars($fileContent) . "</pre>";

        $targetDir = "uploads/";
        $fileName = basename($_FILES["file"]["name"]);
        $targetFilePath = $targetDir . $fileName;
    }
}