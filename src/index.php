<?php
require_once './database.php';
require_once './data.php';

$conn = getDatabaseConnection($servername, $username, $password, $dbname);
if ($conn) {
    $results = fetchData($conn);
    displayData($results);
}
