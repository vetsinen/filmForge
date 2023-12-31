<?php

namespace Webdev\Filmforge;
require_once(__DIR__.'/../config.php');
require_once (__DIR__.'/../vendor/autoload.php');

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
                    $value = trim($s[1]);
                    $film[] = filter_var($value, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $i++;
                } else {
                    $query = "SELECT id AS film_id FROM films WHERE title='$film[0]' AND release_year=$film[1] AND format='$film[2]' LIMIT 1";
                    $rez = $this->genericQuery->fetch($query);
                    if ($rez){$film_id= $rez[0]['film_id'];
                    }
                    else {
                        $query = "INSERT INTO films(title, release_year, format) VALUES('$film[0]',$film[1],'$film[2]')";
                        $film_id = $this->genericQuery->insertAndProvideId($query);
                    }

                    $actors = explode(',', $film[3]);
                    foreach ($actors as $actor) {

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
    public function addFilm()
    {
    }

    public function DeleteFilm()
    {

    }

    public function getByTitle($title)
    {
        $query = "SELECT title, format FROM films WHERE title LIKE '%$title%' ";
        error_log($query);
        return $this->genericQuery->fetch($query);
    }

    public function getByActor($actor)
    {
        $query = "SELECT title, format, fullname FROM films JOIN casted ON films.id = casted.film_id JOIN actors ON casted.actor_id = actors.id  ";
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