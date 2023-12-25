<?php

namespace Webdev\Filmforge;

class FilmModel
{
    public function butchFilmAddingFromTextFile(string $filePath)
    {
        error_log('butch importing');
        $file = fopen($filePath, 'r');

// Check if the file is successfully opened
        if ($file) {
            // Loop through each line of the file
            while (!feof($file)) {
                $line = fgets($file);
                echo $line.'<br>'; // Process the line as needed
            }

            // Close the file handle
            fclose($file);
        } else {
            // Handle the case where the file couldn't be opened
            echo "Unable to open the file.";
        }
    }

    public function addFilm()
    {
    }

    public function DeleteFilm()
    {

    }

    public function getList()
    {

    }

    public function __construct($conn)
    {

    }

    public function getByTitle()
    {

    }

    public function getByActor()
    {

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