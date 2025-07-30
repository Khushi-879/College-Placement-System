<?php
include 'db_connect.php';

$slots = $pdo->query("
    SELECT s.name AS student_name, c.name AS company_name, slot_time
    FROM interview_slots i
    JOIN students s ON i.student_id = s.id
    JOIN companies c ON i.company_id = c.id
    ORDER BY slot_time ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Interview Schedule</title>
</head>
<body>
    <h2>Scheduled Interview Slots</h2>
    <table border="1" cellpadding="10">
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Company</th>
                <th>Slot Time</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($slots as $slot): ?>
            <tr>
                <td><?= htmlspecialchars($slot['student_name']) ?></td>
                <td><?= htmlspecialchars($slot['company_name']) ?></td>
                <td><?= htmlspecialchars($slot['slot_time']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
