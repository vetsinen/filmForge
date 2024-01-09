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
    <title>FilmForge</title>
</head>
<body>
<script src="//unpkg.com/alpinejs" defer></script>

<div class="container" x-data="{
 greeting: 'hello, filmforge',
 addingFilmMode: true,
 title: '',
 release_year: 2020,
 format: 'VHS',
 actors : 'Tom Hanks, Leonardo DiCaprio, Meryl Streep, Denzel Washington, Jennifer Lawrence',
 items: [],
 url: 'api.php/films',
 randomFilmTitle: function () {
  const adjectives = ['beautiful', 'colorful', 'mysterious', 'ancient', 'modern'];
  const nouns = ['landscape', 'adventure', 'journey', 'dream', 'experience'];

  const getRandomItem = array => array[Math.floor(Math.random() * array.length)];

  const randomSentence = `The ${getRandomItem(adjectives)} ${getRandomItem(nouns)} is always a ${getRandomItem(adjectives)} ${getRandomItem(nouns)}.`;

  return randomSentence;
},
 initData: async function (url='api.php/films') {
 this.title = this.randomFilmTitle();
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
},
 postData: async function () {
  const response = await fetch('api.php/films', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({title: this.title, release_year: this.release_year, format: this.format, actors: this.actors})
  });
  this.initData();
  this.addingFilmMode=false;
 },
 deleteFilm: async function (id) {
 const response = await fetch(this.url+'/'+id, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    }
  });
  this.initData();
 }

 }"
 x-init="initData"
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
                <button x-on:click="addingFilmMode=!addingFilmMode" class="button is-info">Add film</button>
            </p>
        </div>
    </div>
    <form x-show="addingFilmMode">
        <div class="field">
            <label class="label">Title</label>
            <div class="control">
                <input class="input" x-model="title" type="text" placeholder="Enter film title">
            </div>
        </div>

        <div class="field">
            <label class="label">Release Year</label>
            <div class="control">
                <input class="input" type="text" x-model="release_year" placeholder="Enter release year">
            </div>
        </div>

        <div class="field">
            <label class="label">Format</label>
            <div class="control">
                <div class="select" x-model="format">
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
                <input class="input" x-model="actors" placeholder="Enter actors' names">
            </div>
        </div>

        <div class="field is-grouped">
            <div class="control">
                <button x-on:click="postData" class="button is-primary" type="button">Submit</button>
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
                <button class="button is-danger" x-on:click="deleteFilm(item.id)">delete film</button>
            </td>

        </tr>
        </template>
        <!-- Add more rows as needed -->
        </tbody>
    </table>

    <div id="upload-file" class="columns">
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
