<?php

function validateFilmData($dbh, $title, $year, $actors)
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

    $findTitlesQuery = $dbh->query('SELECT * FROM films');
    $findTitlesQuery->execute();
    $titles = $findTitlesQuery->fetchAll(PDO::FETCH_COLUMN, 1);

    if (in_array($title,$titles)){
        return 'This title is already exist';
    }

    if (in_array("",$actors)){
        return 'Fill the actors or delete excessive';
    }

    if ($actors !== array_unique($actors)){
        return 'The same actors are selected';
    }

    if ($year < 1940 || $year > 2019 || strlen(trim($year)) !== 4) {
        return 'Enter the correct year';
    }

    return null;
}


function addFilmsFromFile($dbh, array $lines)
{
    for ($i = 0; $i < count($lines); $i++) {
        if ($i % 5 == 0) {
            $titles[] = substr($lines[$i], 7, -1);
        }
        if ($i % 5 == 1) {
            $years[] = substr($lines[$i], 14, -1);
        }
        if ($i % 5 == 2) {
            $formats[] = substr($lines[$i], 8, -1);
        }
        if ($i % 5 == 3) {
            $actorsLines[] = substr($lines[$i], 7, -2);
        }
    }

    for ($i = 0; $i < count($formats); $i++) {
        $insertFilmQuery = $dbh->prepare('INSERT INTO films VALUES(NULL,:title,:year,:formatId)');
        $insertFilmQuery->bindParam(':title', $titles[$i]);
        $insertFilmQuery->bindParam(':year', $years[$i]);
        switch ($formats[$i]) {
            case 'DVD':
                $formatID = 1;
                break;
            case 'VHS':
                $formatID = 2;
                break;
            case 'Blu-Ray':
                $formatID = 3;
        }
        $insertFilmQuery->bindParam(':formatId', $formatID);
        $insertFilmQuery->execute();

        $selectFilmQuery = $dbh->prepare('SELECT LAST_INSERT_ID() AS id');
        $selectFilmQuery->bindParam(':title', $titles[$i]);
        $selectFilmQuery->bindParam(':year', $years[$i]);
        $selectFilmQuery->execute();
        $filmId = $selectFilmQuery->fetch(PDO::FETCH_ASSOC)['id'];

        $actors = explode(",", $actorsLines[$i]);
        foreach ($actors as $key => $actor) {
            $actor = trim($actor);
            $isNewActor = $dbh->prepare('SELECT id FROM actors WHERE fullName = :actorFullName');
            $isNewActor->bindParam(':actorFullName', $actor);
            $isNewActor->execute();
            $actorId = $isNewActor->fetch(PDO::FETCH_ASSOC)['id'];

            if (!$actorId) { // Actor is New
                $insertActorQuery = $dbh->prepare('INSERT INTO actors VALUES(NULL,:fullName)');
                $insertActorQuery->bindParam(':fullName', $actor);
                $insertActorQuery->execute();
                $result = $dbh->query('SELECT LAST_INSERT_ID() AS id');
                $actorId = $result->fetch(PDO::FETCH_ASSOC)['id'];
            }

            $insertIntoConnectTable = $dbh->prepare('INSERT INTO movies_actors VALUES(:movie_id,:actor_id)');
            $insertIntoConnectTable->bindParam(':movie_id', $filmId);
            $insertIntoConnectTable->bindParam(':actor_id', $actorId);
            $insertIntoConnectTable->execute();
        }
    }
}
