<?php
include 'db_connect.php';

$company_id = $_POST['company_id'];

// Get seat limit
$stmt = $conn->prepare("SELECT seat_limit FROM companies WHERE id = ?");
$stmt->bind_param("i", $company_id);
$stmt->execute();
$stmt->bind_result($seat_limit);
$stmt->fetch();
$stmt->close();

// Get applications ordered by application_time
$sql = "SELECT id FROM applications WHERE company_id = ? AND status = 'Applied' ORDER BY application_time ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();

$count = 0;
while ($row = $result->fetch_assoc()) {
    $status = ($count < $seat_limit) ? 'Selected' : 'Waiting';
    $update = $conn->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $update->bind_param("si", $status, $row['id']);
    $update->execute();
    $update->close();
    $count++;
}
?>
