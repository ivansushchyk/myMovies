<?php

function validateFilmDate($title, $year, $actors)
{
    if (strlen($title) == 0 || strlen($year) == 0 ) {
        return 'Fill the empty fields';
    }
    if (in_array("",$actors)){
        return 'Fill the actors or delete excessive';
    }

    if (strlen($title) < 3 or strlen($title) > 25) {
        return 'Length of name must be bigger than 2 and less than 25';
    }

    if ($year < 1950 || $year > 2019 || strlen(trim($year)) !== 4) {
        return 'Enter the correct year';
    }




    return null;
}