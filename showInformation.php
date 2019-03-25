<?php

require('databaseConnect.php');
$id = $_GET['film_id'];
$film = $dbh->prepare('select * from films where id = :id;');
$film->bindParam(':id', $id);
$film->execute();
$film = $film->fetch(PDO::FETCH_ASSOC);

$format = $dbh->prepare('select formats.name from films INNER JOIN formats ON formats.id = :id ;');
$format->bindParam(':id', $film['format_id']);
$format->execute();
$format = $format->fetch(PDO::FETCH_ASSOC);

$actors = $dbh->prepare('select fullName from actors where id IN( Select actor_id from movies_actors where movie_id = :id)');
$actors->bindParam(':id', $id);
$actors->execute();
$actors = $actors->fetchAll(PDO::FETCH_COLUMN, 0);
?>
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
    </style>
</head>
<body>
<nav class="navbar navbar-default">
    <a class="navbar-brand" href="/addFilm.php">Add a film</a>
    <a id="middle-element" class="navbar-brand active" href="/index.php"> Main page </a>
</nav>

<h1 class="text-center"> Information about film "<?= htmlspecialchars($film['title']) ?>"</h1>
<hr>
<h2 class="text-center"> Release year: <?= htmlspecialchars($film['year']) ?> </h2>
<hr>
<h2 class="text-center"> Format: <?= htmlspecialchars($format['name']) ?> </h2>
<hr>
<h2 class="text-center"> Actors: <?php if (!$actors) {
        echo 'no information';
    }
    else {
    ?> </h2>
<hr>
<ul class="list-inline text-center">
    <?php foreach ($actors as $actor): ?>
        <li class="list-group-item text-center"> <?= htmlspecialchars($actor) ?> </li>
    <?php endforeach; ?>
</ul>

<?php } ?>








