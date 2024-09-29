<?php
require_once "./database/global.php";

$conn = new mysqli($host, $username, $password, $database);

$sql = "
    SELECT 
        assignments.assignment_id, 
        assignments.professor_id, 
        assignments.course_id,
        professors.first_name,
        professors.last_name,
        professors.email,
        courses.course_name,
        courses.credits,
        courses.department
    FROM assignments
    LEFT JOIN professors
    ON assignments.professor_id = professors.professor_id
    LEFT JOIN courses
    ON assignments.course_id = courses.course_id
";

$results = $conn->query($sql);

$assignments = [];

if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        array_push($assignments, [
            'assignment_id' => $row['assignment_id'],
            'course_name' => $row['course_name'],
            'department' => $row['department'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
        ]);
    }
}

$conn->close();
