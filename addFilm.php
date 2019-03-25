<?php
require('functions.php');
require('databaseConnect.php');

$formats = $dbh->query('SELECT * FROM formats')->fetchAll(); // Formats for selects

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // IF WE SUBMIT A FORM
    $title = $_POST['title'];
    $year = $_POST['year'];
    $formatID = $_POST['format'];
    $actors = $_POST['actors'];
    $validationErrors = validateFilmData($dbh, $title, $year, $actors);
    if (!$validationErrors) {
        $insertFilmQuery = $dbh->prepare('INSERT INTO films (title, year, format_id) VALUES (:title,:year,:formatId)');
        $insertFilmQuery->bindParam(':title', $title);
        $insertFilmQuery->bindParam(':year', $year);
        $insertFilmQuery->bindParam(':formatId', $formatID);
        $insertFilmQuery->execute();
        $lastId = ($dbh->query('SELECT LAST_INSERT_ID()')->fetchAll())[0][0];

        foreach ($actors as $actor) {
            $findIdActor = $dbh->prepare('SELECT id FROM actors WHERE fullName = :fullName'); // IF ACTOR IS EXIST
            $findIdActor->bindParam(':fullName', $actor);
            $findIdActor->execute();
            $actorId = $findIdActor->fetch(PDO::FETCH_ASSOC)['id'];

            if (!$actorId) {
                $insertActorQuery = $dbh->prepare('INSERT INTO actors VALUES(NULL,:fullName)');
                $insertActorQuery->bindParam(':fullName', $actor);
                $insertActorQuery->execute();

                $getLastInsertIdQuery = $dbh->query('SELECT LAST_INSERT_ID() AS id');
                $actorId = $getLastInsertIdQuery->fetch(PDO::FETCH_ASSOC)['id'];
            }

            $insertIntoConnectTable = $dbh->prepare('INSERT INTO movies_actors VALUES(:movie_id,:actor_id)');
            $insertIntoConnectTable->bindParam(':movie_id', $lastId);
            $insertIntoConnectTable->bindParam(':actor_id', $actorId);
            $insertIntoConnectTable->execute();
        }

        header('Location:index.php');
    }

} else {
    $title = "";
    $actors = ['', '', ''];
    $year = '';
    $validationErrors = '';
} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My movies</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        #middle-element {
            margin: 0 auto;
            text-align: center;
            width: 1700px;
        }

        .actors-wrapper {
            width: 93%;
            display: inline;
        }
    </style>

</head>
<body>
<nav class="navbar navbar-default">
    <a class="navbar-brand" href="/addFilm.php">Add a film</a>
    <a id="middle-element" class="navbar-brand active" href="/index.php"> Main page </a>
</nav>
<div class="container">
    <div class="row">
        <h1 class="text-center"> Adding Film </h1>
        <hr>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div id="form">
                <form id=addingForm action="addFilm.php" method="post">
                    <h3 class="text-center"> Title </h3>

                    <input class="form-control input-sm" value="<?= htmlspecialchars($title) ?>" id="title" name="title"
                           type="text">
                    <h3 class="text-center"> Year </h3>
                    <input class="form-control input-sm" value="<?= $year ?>" id="year" name="year"
                           type="text">

                    <h3 class="text-center"> Format </h3>
                    <select class="form-control" name="format">
                        <?php foreach ($formats as $format): ?>
                            <option value="<?= htmlspecialchars($format['id']) ?>"><?= htmlspecialchars($format['name']) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <h3 class="text-center"> Actors </h3>
                    <div id="actors-wrappers">
                        <?php foreach ($actors as $actor): ?>
                            <div id="actor-div" class="actor-wrappers">
                                <input class="form-control input-sm actors-wrapper" value="<?= $actor ?>"
                                       name="actors[]"
                                       type="text">
                                <button type="button" onclick="delete_row(this)">-</button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <button type="button" id="addActorForm" onclick="addItem()">+</button>

                    <div class="text-center" style="margin-top: 20px">
                        <button type="submit" class="btn btn-info text-center">Add film</button>
                    </div>


                </form>


                <?php if ($validationErrors): ?>
                    <div class="alert alert-warning">
                        <?= htmlspecialchars($validationErrors) ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<script>
    function addItem() {
        var div = document.createElement('div');
        div = document.getElementById('actor-div').cloneNode(true);
        var parentDiv = document.getElementById('actors-wrappers');
        parentDiv.appendChild(div);
        return false;
    }

    function delete_row(e) {
        e.parentNode.parentNode.removeChild(e.parentNode);
    }
</script>
