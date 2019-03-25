<?php

require('functions.php');
require('databaseConnect.php');

$lines = file($_FILES['films_file']['tmp_name']);
addFilmsFromFile($dbh, $lines);
header("Location: index.php");
die;