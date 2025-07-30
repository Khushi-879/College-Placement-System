<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['company_name'];
    $job_title = $_POST['job_title'];
    $job_description = $_POST['job_description'];
    $min_cgpa = $_POST['min_cgpa'];
    $interview_date = $_POST['interview_date'];
    $seat_limit = $_POST['seat_limit'];

    $stmt = $conn->prepare("INSERT INTO companies (name, job_title, job_description, min_cgpa, interview_date, seat_limit) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssdsd", $name, $job_title, $job_description, $min_cgpa, $interview_date, $seat_limit);
    $stmt->execute();
    $stmt->close();

    echo "Company job posting added successfully.";
}
?>
