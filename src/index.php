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
 title: '',
 release_year: 2020,
 format: 'VHS',
 actors : '',
 items: [],
 randomFilmTitle: function () {
  const adjectives = ['beautiful', 'colorful', 'mysterious', 'ancient', 'modern'];
  const nouns = ['landscape', 'adventure', 'journey', 'dream', 'experience'];

  const getRandomItem = array => array[Math.floor(Math.random() * array.length)];

  const randomSentence = `The ${getRandomItem(adjectives)} ${getRandomItem(nouns)} is always a ${getRandomItem(adjectives)} ${getRandomItem(nouns)}.`;

  return randomSentence;
}
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


    <div class="columns">
        <div class="column">
            <p class="has-text-left">
            <h1 class="title" x-text="greeting">
                Films Page
            </h1>
            </p>
        </div>
        <div class="column">
            <p class="has-text-right">
                <button class="button is-info">Add film</button>
            </p>
        </div>
    </div>
    <form>
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" x-model="title" type="text" placeholder="Enter film title">
            </div>
        </div>

        <div class="field">
            <label class="label">Release Year</label>
            <div class="control">
                <input class="input" type="text" value="2020" placeholder="Enter release year">
            </div>
        </div>

        <div class="field">
            <label class="label">Format</label>
            <div class="control">
                <div class="select">
                    <select>
                        <option>Select format</option>
                        <option>DVD</option>
                        <option selected>Blu-ray</option>
                        <option>VHS</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label">Actors</label>
            <div class="control">
                <textarea class="textarea" placeholder="Enter actors' names">Tom Hanks, Leonardo DiCaprio, Meryl Streep, Denzel Washington, Jennifer Lawrence</textarea>
            </div>
        </div>

        <div class="field is-grouped">
            <div class="control">
                <button class="button is-primary" type="submit">Submit</button>
            </div>
            <div class="control">
                <button class="button is-link" type="reset">Reset</button>
            </div>
        </div>
    </form>

    <table class="table is-fullwidth">
        <thead>
        <tr>
            <th>Title</th>
            <th>Year</th>
            <th>Format</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <template x-for="item in items" :key="item.id">
        <tr>
            <td x-text="item.title">Movie 1</td>
            <td x-text="item.release_year">2022</td>
            <td x-text="item.format">DVD</td>
            <td>
                <button class="button is-primary">see details</button>
                <button class="button is-danger">delete film</button>
            </td>

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
