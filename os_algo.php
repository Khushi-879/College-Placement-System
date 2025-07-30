<?php
include 'db.php';

$sql = "SELECT * FROM students ORDER BY applied_time ASC";
$result = $conn->query($sql);

echo "<h2>Placement Scheduling</h2>";
echo "<table border='1'><tr><th>Name</th><th>Applied Time</th><th>Scheduled Slot</th></tr>";

$time = 9;
while($row = $result->fetch_assoc()) {
    echo "<tr><td>".$row['name']."</td><td>".$row['applied_time']."</td><td>".$time.":00 AM</td></tr>";
    $time++;
}
echo "</table>";
?>
