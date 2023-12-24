<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');

enum FilmFormat
{
    case VHS;
    case DVD;
    case BlueRay;
}

$log = new Monolog\Logger('name');
$film = new Webdev\Filmforge\Film();
$conn = new Webdev\Filmforge\MySQLConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>Simple Bulma Page</title>
</head>
<body>

<section class="section">
    <div class="container">
        <h1 class="title">
            Welcome to My Bulma Page
        </h1>
        <p class="subtitle">
            Simple HTML page styled with Bulma CSS
        </p>
    </div>
</section>

<div class="container">
    <div class="columns">
        <div class="column">
            <div class="box">
                <p class="title is-5">uploading column</p>
                <form action="upload.php" method="post" enctype="multipart/form-data">
                    <!-- File input -->
                    <div class="field">
                        <label class="label">Choose a text file</label>
                        <div class="control">
                            <input class="input" type="file" name="file">
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div class="field">
                        <div class="control">
                            <button type="submit" class="button is-primary">Upload</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
