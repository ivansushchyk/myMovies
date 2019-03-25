<?php
require('functions.php');
require('databaseConnect.php');

$formats = $dbh->query('SELECT * FROM formats')->fetchAll(); // Formats for selects

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  // IF WE SUBMIT A FORM
    $title = $_POST['title'];
    $year = $_POST['year'];
    $formatID = $_POST['format'];
    $actors = $_POST['actors'];
    $validationErrors = validateFilmDate($title, $year, $formatID);
    if (!$validationErrors) {
        $insertContactQuery = $dbh->prepare('INSERT INTO films VALUES(NULL,:title,:year,:formatId)');
        $insertContactQuery->bindParam(':title', $title);
        $insertContactQuery->bindParam(':year', $year);
        $insertContactQuery->bindParam(':formatId', $formatID);
        $insertContactQuery->execute();
        header('Location:index.php');
    }
} else {
    $title = '';
    $year = '';
    $format = '';
    $actors = ['', '', ''];
}
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
                    <input class="form-control input-sm" value="<?= htmlspecialchars($year) ?>" id="year" name="year" type="text">

                    <h3 class="text-center"> Format </h3>
                    <select class="form-control" name="format">
                        <?php foreach ($formats as $format): ?>
                            <option value="<?= htmlspecialchars($format['id']) ?>"><?= htmlspecialchars($format['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
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

