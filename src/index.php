<?php
session_start();
//$_SESSION['userid']=0;
require_once(__DIR__ . '/vendor/autoload.php');

$log = new Monolog\Logger('name');

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
<script src="main.js"></script>

<div class="container" x-data="filmForge"
 x-init="initData">

    <div  id="auth" x-show="authMode" class="columns">
        <!-- Login Column -->
        <div id="login" class="column is-half">
            <div class="box">
                <h1 class="title is-4 has-text-centered">Login</h1>
                <!-- Login Form -->
                <form  action="" method="post">
                    <div class="field">
                        <label class="label">Username</label>
                        <div class="control">
                            <input x-model="username" class="input" type="text" name="username" placeholder="Enter your username" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input x-model="password" class="input" type="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button x-on:click="login" class="button is-primary is-fullwidth" type="button">Login</button>
                        </div>
                    </div>
                </form>
                <!-- End Login Form -->

            </div>
        </div>

        <!-- Register Column -->
        <div id="register" class="column is-half">
            <div class="box">
                <h1 class="title is-4 has-text-centered">User Registration</h1>

                <!-- Registration Form -->
                <form action="register.php" method="post">
                    <div class="field">
                        <label class="label">Username</label>
                        <div class="control">
                            <input x-model="username" class="input" type="text" name="username" placeholder="Enter your username" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input x-model="password" class="input" type="password" name="password" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Confirm Password</label>
                        <div class="control">
                            <input x-model="password2" class="input" type="password" name="confirm_password" placeholder="Confirm your password" required>
                        </div>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button x-on:click="register" class="button is-primary is-fullwidth" type="button">Register</button>
                        </div>
                    </div>
                </form>
                <!-- End Registration Form -->

            </div>
        </div>
    </div>

    <div id="header" class="columns">
        <div class="column">
            <p class="has-text-left">
            <h1 class="title">
                <span x-text="greeting">Films Page</span><span>, user id is: <span x-text="userid"></span></span>
            </h1>
            </p>
        </div>
        <div class="column">
            <p class="has-text-right">
                <button x-on:click="authMode=!authMode" class="button is-light">show/hide login and register form</button>
                <button x-show="userid" x-on:click="logout" class="button is-light is-small">Logout</button>
                <button x-show="userid>0" x-on:click="addingFilmMode=!addingFilmMode" class="button is-light">show/hide film adding form</button>
                <button x-on:click="ffocus('film'+158)">goto film</button>
            </p>
        </div>
    </div>

    <form id="filmAddingForm" x-show="addingFilmMode">
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

    <form id="filmSearchForm">
        <div class="field">
            <label class="label">search by title</label>
            <div class="control">
                <input class="input" x-model="stitle" type="text" placeholder="Enter film title">
            </div>
            <label class="label">search by actor name</label>
            <div class="control">
                <input class="input" x-model="sfullname" type="text" placeholder="Enter actor fullname">
            </div>
        </div>
    </form>

    <table id="films" class="table is-fullwidth">
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
                <a x-bind:id="'film'+item.id"></a>
                <button class="button is-primary" x-on:click="getFilmDetails(item.id)">see details</button>
                <button x-show="userid===item.user_id" class="button is-danger" x-on:click="deleteFilm(item.id)">delete film</button>
            </td>

        </tr>
        </template>
        <!-- Add more rows as needed -->
        </tbody>
    </table>

    <div x-show="userid>0" id="upload-file" class="columns">
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
