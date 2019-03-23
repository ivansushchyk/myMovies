<?php
$user = 'root';
$pass = 'root';
$dbh = new PDO('mysql:host=localhost;dbname=movies', $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$result = $dbh->query('SELECT * FROM films');
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
            width: 1700px;
        }

        #aside {
            width: 300px;
            float: right;

        }

        #title {
            margin-right: 300px;
            border-top: none;

        }
    </style>
</head>
<body>
<nav class="navbar navbar-default">
    <a class="navbar-brand" href="#">Add a film</a>
    <a id="middle-element" class="navbar-brand active" href="#"> My movies library </a>
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

    <div id="title">
        <br>
        <h1 style="margin-left:885px; border: 3px"> Films list </h1>
    </div>
</div>

<div style="margin-top: 30px">
<hr>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Films</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
       <?php foreach ($result as $task): ?>
        <tr>
            <th scope="row"> <?php echo $task['title']."(".$task['year'].")"; ?> </th>
            <td>
                <a href="tasks/{{ $task->id }}" class="btn btn-primary"> Show </a>
                <form class="inline-form" method="post" action="tasks/{{$task->id}}">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="submit" class="btn btn-danger" value="Delete">
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>


</body>
</html>
