<?php
require "databaseConnect.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') { // DELETE REQUEST FROM FORM IN BOTTOM

    $deleteInConnectingTable = $dbh->prepare('DELETE FROM movies_actors where movie_id = :deletedId');
    $deleteInConnectingTable->bindParam(':deletedId', $_POST['deleted_id']);
    $deleteInConnectingTable->execute();

    $deleteFilmQuery = $dbh->prepare('DELETE FROM films where id = :deletedId');
    $deleteFilmQuery->bindParam(':deletedId', $_POST['deleted_id']);
    $deleteFilmQuery->execute();

}

$films = $dbh->query('SELECT * FROM films ORDER BY title');

if (isset($_GET['search_query']) && strlen($_GET['search_type']) !== 0) {

    $searchType = $_GET['search_type'];
    $searchQuery = $_GET['search_query'];

    if ($searchType == 'movie') {  // SEARCH BY MOVIE
        $films = $dbh->prepare('SELECT * FROM films WHERE title LIKE :title ORDER BY title');
        $LikeSearchQuery = "%" . $searchQuery . "%";
        $films->bindParam(':title', $LikeSearchQuery);
        $films->execute();
        $films = $films->fetchAll();
    } else {   // SEARCH BY ACTOR
        $films = $dbh->prepare('SELECT * from films WHERE id IN(SELECT movie_id from movies_actors WHERE actor_id IN
                      (SELECT id from actors WHERE fullName LIKE :actor));');
        $LikeSearchQuery = "%" . $searchQuery . "%";
        $films->bindParam(':actor', $LikeSearchQuery);
        $films->execute();
        $films = $films->fetchAll();
    }


} else {

}
$searchQuery = '';
$year = '';
$title = '';
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
    <a id="middle-element" class="navbar-brand active" href="/index.php"> My movies library </a>
</nav>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h4 class="text-center">Search by: </h4>
            <form>
                <p class="text-center">
                    <label class="radio-inline">
                        <input type="radio" name="search_type" value="movie">Movie title
                    </label>

                    <label class="radio-inline">
                        <input type="radio" name="search_type" value="actor">Name actor
                    </label>
                </p>
                <input style="width: 80%; display: inline-block" name="search_query" type="text"
                       value="<?= htmlspecialchars($searchQuery) ?>" class="form-control" placeholder="Search">
                <button class="btn btn-default" type="submit">
                    <i class="glyphicon glyphicon-search"></i>
                </button>
            </form>
        </div>
        <div class="col-md-2 col-md-offset-1">
            <h4>Add films from file</h4>
            <form action="/addFilmsFromFile.php" enctype="multipart/form-data" method="post">
                <input type="file" name="films_file">
                <button type="submit" class="btn btn-info" style="margin-top: 5px">Submit file</button>
            </form>
        </div>
    </div>

    <div class="row">
    </div>
</div>


<div style="margin-top: 30px">
    <hr>
    <?php if (!$films) { ?>

        <h1 class="text-center"> No result for your request </h1>

    <?php } else {
        ; ?>


        <table class="table">
            <thead>
            <tr>
                <th scope="col">Films</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($films as $film): ?>
                <tr>
                    <th scope="row"> <?= htmlspecialchars($film['title']) . "(" . $film['year'] . ")"; ?> </th>
                    <td>
                        <a href="/showInformation.php?film_id=<?= $film['id'] ?>" class="btn btn-primary"> Show
                            information </a>
                        <form class="inline-form" method="post">
                            <input type="hidden" name="deleted_id" value="<?= $film['id'] ?>">
                            <input type="submit" class="btn btn-danger"
                                   onclick="return confirm('Do you really want to delete this film?');" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php } ?>

</div>
</body>
</html>


