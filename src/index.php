<?php
session_start();
require_once(__DIR__ . '/vendor/autoload.php');

$log = new Monolog\Logger('name');
$film = new Webdev\Filmforge\Film();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">
    <title>Films Page</title>
</head>
<body>
<script src="//unpkg.com/alpinejs" defer></script>

<div class="container" x-data="{
 greeting: 'hello, filmforge',
 items: [],
 fetchData: async function (url='api.php/films') {
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const rez = await response.json();
    console.log(rez.items);
    this.items = rez.items;
  } catch (error) {
    console.error('Error:', error.message);
  }
}

 }"
 x-init="fetchData"
>
    <h1 class="title" x-text="greeting">
        Films Page
    </h1>
    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Title</th>
            <th>Year</th>
            <th>Format</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="item in items" :key="item.id">
        <tr>
            <td x-text="item.title">Movie 1</td>
            <td x-text="item.release_year">2022</td>
            <td x-text="item.format">DVD</td>
        </tr>
        </template>
        <!-- Add more rows as needed -->
        </tbody>
    </table>

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
