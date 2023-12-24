<?php
require_once (__DIR__ .'/vendor/autoload.php');

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
                <p class="title is-5">Column 1</p>
                <p>This is some content for column 1.</p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="title is-5">Column 2</p>
                <p>This is some content for column 2.</p>
            </div>
        </div>
        <div class="column">
            <div class="box">
                <p class="title is-5">Column 3</p>
                <p>This is some content for column 3.</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
