<?php
require_once "./database/global.php";

$conn = new mysqli($host, $username, $password, $database);

$sql = "SELECT * FROM courses";

$results = $conn->query($sql);

$courses = [];

if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        array_push($courses, [
            'course_id' => $row['course_id'],
            'course_name' => $row['course_name'],
            'credits' => $row['credits'],
            'department' => $row['department']
        ]);
    }
}

$conn->close();
