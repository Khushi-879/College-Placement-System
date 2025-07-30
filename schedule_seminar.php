<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO seminars (title, date, time, venue, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $title, $date, $time, $venue, $description);
    $stmt->execute();
    $stmt->close();

    echo "Seminar scheduled successfully.";
}
?>
