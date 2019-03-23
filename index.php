<?php
$user = 'root';
$pass = 'root';
$dbh = new PDO('mysql:host=localhost;dbname=movies', $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reference book</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        #middle-element {
            margin: 0 auto;
            text-align: center;
            width: 1550px;
        }

        #aside {
            width: 300px;
            float: right;

        }

        #content {
            margin-right: 300px;
            border-top: none;

        }
    </style>
</head>
<body>
<nav class="navbar navbar-default">
    <a class="navbar-brand" href="#">Add a film</a>
    <a id="middle-element" class="navbar-brand active" href="#">My movies library </a>
</nav>
<div>
    <div id="aside">
        <h4 class="text-center">Search by: </h4>
        <form>
            <p class="text-center">
                <label class="radio-inline"><input type="radio" name="optradio">Name actor</label>
                <label class="radio-inline"><input type="radio" name="optradio">Movie title</label>

            </p>

            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search">
                <div class="input-group-btn">
                    <button class="btn btn-default" type="submit">
                        <i class="glyphicon glyphicon-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div id="content">
         <h1 style="margin-left:800px;"> Films list </h1>
        <hr>
    </div>
</div>


</body>
</html>

