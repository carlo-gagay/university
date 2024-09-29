<?php

require_once "./database/global.php";

function createDatabase() {
    global $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password);

    if ($conn->connect_error) {
        die("Failed to stablish connection: " . $conn->connect_error);
    }

    $sql = "CREATE DATABASE IF NOT EXISTS {$database}";
    
    if (! $conn->query($sql)) {
        die("Create databse error.");
    }

    $conn->close();
}

function createTable() {
    global $host, $username, $password, $database;

    $conn = new mysqli($host, $username, $password, $database);

    if (!$conn) {
        die("Failed to stablish connection" . mysqli_connect_error());
    }
    
    $students = "
        CREATE TABLE IF NOT EXISTS students (
            student_id INT PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            dob DATE,
            email VARCHAR(100) UNIQUE
        )
    ";

    if (! $conn->query($students)) {
        die("Create tables failed." . $conn->error);
    }

    $courses = "
        CREATE TABLE IF NOT EXISTS courses (
            course_id INT PRIMARY KEY AUTO_INCREMENT,
            course_name VARCHAR(100) NOT NULL,
            credits INT NOT NULL,
            department VARCHAR(100) NOT NULL
        )
    ";

    if (! $conn->query($courses)) {
        die("Create tables failed." . $conn->error);
    }

    $professors = "
        CREATE TABLE IF NOT EXISTS professors (
            professor_id INT PRIMARY KEY AUTO_INCREMENT,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            department VARCHAR(100) NOT NULL,
            email VARCHAR(100) UNIQUE
        )
    ";
    
    if (! $conn->query($professors)) {
        die("Create tables failed." . $conn->error);
    }

    $enrollments = "
        CREATE TABLE IF NOT EXISTS enrollments (
            enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
            student_id INT,
            course_id INT,
            enrollment_date DATE,
            FOREIGN KEY (student_id) REFERENCES students(student_id),
            FOREIGN KEY (course_id) REFERENCES courses(course_id)
        )
    ";
    
    if (! $conn->query($enrollments)) {
        die("Create tables failed." . $conn->error);
    }

    $assignments = "
        CREATE TABLE IF NOT EXISTS assignments (
            assignment_id INT PRIMARY KEY AUTO_INCREMENT,
            professor_id INT,
            course_id INT,
            FOREIGN KEY (professor_id) REFERENCES professors(professor_id),
            FOREIGN KEY (course_id) REFERENCES courses(course_id)
        )
    ";
    
    if (! $conn->query($assignments)) {
        die("Create tables failed." . $conn->error);
    }

    $conn->close();
}

createDatabase();
createTable();
?>