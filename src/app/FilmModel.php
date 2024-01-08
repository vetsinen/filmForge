<?php

namespace Webdev\Filmforge;
require_once(__DIR__.'/../config.php');
require_once (__DIR__.'/../vendor/autoload.php');
require_once (__DIR__.'/../validate.php');

class FilmModel
{
    private GenericQuery $genericQuery;

    public function butchFilmAddingFromTextFile(string $filePath)
    {
        error_log('butch importing');
        $file = fopen($filePath, 'r');
        if ($file) {
            $i = 1; $film =[];
            while (!feof($file)) {
                $line = str_replace("\n", "", fgets($file));
                if ($i < 5) {
                    $s = explode(":", $line, 2);
                    if (sizeof($s)!==2) break;
                    $film[] = clearString($s[1]);
                    $i++;
                } else {
                    $actors = explode(',', $film[3]);


                    $i = 1; $film = [];
                }

            }
            fclose($file);
        } else {
            throw new \Exception("Unable to open the file.");
        }
    }
    public function getList()
    {
        $query = "SELECT title, release_year, format FROM films ORDER BY title LIMIT ".strval( ITEMS_PER_PAGE);
        return  $this->genericQuery->fetch($query);
    }
    public function addFilm($film)
    {
        error_log('trying to insert '.json_encode($film));
        $query = "SELECT id AS film_id FROM films WHERE title='$film[title]' AND release_year=$film[release_year] AND format='$film[format]' LIMIT 1";
        $rez = $this->genericQuery->fetch($query);
        if ($rez) {$film_id= $rez[0]['film_id'];}
        else
        {
            $query = "INSERT INTO films(title, release_year, format) VALUES('$film[title]',$film[release_year],'$film[format]')";
            $film_id = $this->genericQuery->insertAndProvideId($query);
        }

        $actors = explode(',', $film['actors']);
        foreach ($actors as $actor) {
            $actor = mb_trim($actor);
            if (strlen($actor)<2) continue;
            $query = "SELECT id as actor_id FROM actors WHERE fullname='$actor' LIMIT 1";
            $rez = $this->genericQuery->fetch($query);
            if (!$rez) {
                $query = "INSERT INTO actors(fullname) VALUES('$actor')";
                $actor_id = $this->genericQuery->insertAndProvideId($query);
            } else {
                $actor_id = $rez[0]['actor_id'];
            }
            $query = "INSERT IGNORE INTO casted(film_id, actor_id) VALUES ($film_id, $actor_id)";
            $this->genericQuery->execute($query);
        }
        return $film_id;
    }

    public function DeleteFilm($id)
    {
        $query = "DELETE FROM films WHERE id=$id";
        $this->genericQuery->execute($query);
        $query = "DELETE FROM casted WHERE film_id=$id";
        $this->genericQuery->execute($query);
    }

    public function getByTitle($title)
    {
        $query = "SELECT title, format FROM films WHERE title = '$title' ";
        error_log($query);
        return $this->genericQuery->fetch($query);
    }

    public function getByActor($actor)
    {
        $query = "SELECT title, format, release_year, fullname FROM films JOIN casted ON films.id = casted.film_id JOIN actors ON casted.actor_id = actors.id HAVING fullname='$actor'";
        error_log($query);
        return $this->genericQuery->fetch($query);
    }
    public function __construct($conn)
    {
        $this->genericQuery = $conn;
    }
}
//2. Додати фільм
//3. Видалити фільм
//4. Показати інформацію про фільм
//5. Показати список фільмів відсортованих за назвою в алфавітному порядку
//6. Знайти фільм за назвою.
//7. Знайти фільм на ім'я актора.
//8. Імпорт фільмів із текстового файлу (приклад файлу надається
//“sample_movies.txt”). Файл повинен завантажуватись через веб-інтерфейс.
//Насамперед важливо, щоб була реалізована вся функціональність з погляду
//користувача.