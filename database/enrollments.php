<?php
require_once "./database/global.php";

$conn = new mysqli($host, $username, $password, $database);

$sql = "
    SELECT 
        enrollments.enrollment_id, 
        enrollments.student_id, 
        enrollments.course_id,
        enrollments.enrollment_date,
        students.first_name,
        students.last_name,
        students.dob,
        students.email,
        courses.course_name,
        courses.credits,
        courses.department
    FROM enrollments
    LEFT JOIN students
    ON enrollments.student_id = students.student_id
    LEFT JOIN courses
    ON enrollments.course_id = courses.course_id
";

$results = $conn->query($sql);

$enrollments = [];

if ($results->num_rows > 0) {
    while ($row = $results->fetch_assoc()) {
        array_push($enrollments, [
            'enrollment_id' => $row['enrollment_id'],
            'course_name' => $row['course_name'],
            'department' => $row['department'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'enrollment_date' => $row['enrollment_date'],
            'dob' => $row['dob'],
            'email' => $row['email'],
        ]);
    }
}

$conn->close();
