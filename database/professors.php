<?php
require_once "./database/global.php";

$conn = new mysqli($host, $username, $password, $database);

$sql = "SELECT * FROM professors";

$results = $conn->query($sql);

$professors = [];

if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        array_push($professors, [
            'professor_id' => $row['professor_id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'department' => $row['department'],
            'email' => $row['email'],
        ]);
    }
}

$conn->close();
