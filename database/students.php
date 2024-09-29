<?php
require_once "./database/global.php";

$conn = new mysqli($host, $username, $password, $database);

$sql = "SELECT * FROM students";

$results = $conn->query($sql);

$students = [];

if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        array_push($students, [
            'student_id' => $row['student_id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'dob' => $row['dob'],
            'email' => $row['email'],
        ]);
    }
}

$conn->close();
