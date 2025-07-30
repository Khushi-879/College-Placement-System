<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = "college_placement";

$conn = mysqli_connect($host, $user, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}



// Initialize slot time and interval
$slot_start_time = new DateTime('2025-05-25 09:00:00');
$slot_interval = new DateInterval('PT30M'); 

// Get all companies
$companies_result = mysqli_query($conn, "SELECT * FROM companies");
if (!$companies_result) die("Error fetching companies: " . mysqli_error($conn));

$companies = [];
while ($row = mysqli_fetch_assoc($companies_result)) {
    $companies[] = $row;
}

$eligibleStudents = [];
$studentsCount = [];

// Fetch eligible students per company
foreach ($companies as $company) {
    $company_id = $company['id'];
    $min_cgpa = $company['min_cgpa'];

    $stmt = mysqli_prepare($conn, "
        SELECT s.id, s.name FROM students s
        INNER JOIN applications a ON s.id = a.student_id
        WHERE a.company_id = ? AND s.cgpa >= ?
        ORDER BY s.id ASC
    ");
    mysqli_stmt_bind_param($stmt, "id", $company_id, $min_cgpa);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $students = [];
    while ($student = mysqli_fetch_assoc($result)) {
        $students[] = $student;
    }
    mysqli_stmt_close($stmt);

    $eligibleStudents[$company_id] = $students;
    $studentsCount[$company_id] = count($students);
}

// Initialize pointers to track next student per company
$pointers = [];
foreach ($companies as $company) {
    $pointers[$company['id']] = 0;
}

$totalScheduled = 0;
$scheduledThisRound = true;

// Round robin scheduling loop
while ($scheduledThisRound) {
    $scheduledThisRound = false;

    foreach ($companies as $company) {
        $company_id = $company['id'];
        $students = $eligibleStudents[$company_id];
        $pointer = $pointers[$company_id];

        if ($pointer < $studentsCount[$company_id]) {
            $student = $students[$pointer];
            $student_id = $student['id'];

            // Check if already scheduled
            $chk_stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM interview_schedule WHERE student_id = ? AND company_id = ?");
            mysqli_stmt_bind_param($chk_stmt, "ii", $student_id, $company_id);
            mysqli_stmt_execute($chk_stmt);
            mysqli_stmt_bind_result($chk_stmt, $count);
            mysqli_stmt_fetch($chk_stmt);
            mysqli_stmt_close($chk_stmt);

            if ($count > 0) {
                // Already scheduled, skip and move pointer
                $pointers[$company_id]++;
                continue;
            }

            // Insert interview schedule record
            $insert_stmt = mysqli_prepare($conn, "
                INSERT INTO interview_schedule 
                (student_id, company_id, interview_date, interview_time, mode, location, status)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $interview_date = $slot_start_time->format('Y-m-d');
            $interview_time = $slot_start_time->format('H:i:s');
            $mode = 'Offline';
            $location = 'College Campus';
            $status = 'Scheduled';

            mysqli_stmt_bind_param(
                $insert_stmt,
                "iisssss",
                $student_id,
                $company_id,
                $interview_date,
                $interview_time,
                $mode,
                $location,
                $status
            );

            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            // Move pointer and slot time forward
            $pointers[$company_id]++;
            $slot_start_time->add($slot_interval);
            $scheduledThisRound = true;
            $totalScheduled++;
        }
    }
}



$sql = "
    SELECT 
        i.id, s.name AS student_name, c.name AS company_name, 
        i.interview_date, i.interview_time, i.mode, i.location, i.status, i.remarks
    FROM interview_schedule i
    JOIN students s ON i.student_id = s.id
    JOIN companies c ON i.company_id = c.id
    ORDER BY i.interview_date, i.interview_time
";

$result = mysqli_query($conn, $sql);
if (!$result) die("Error fetching schedules: " . mysqli_error($conn));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Interview Schedule</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef2f7; padding: 30px; }
        table { border-collapse: collapse; width: 100%; max-width: 1000px; margin: auto; background: white; }
        th, td { border: 1px solid #ccc; padding: 12px 15px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        h2 { text-align: center; margin-bottom: 20px; }
        .message { text-align: center; color: green; font-weight: bold; margin-bottom: 20px; }
    </style>
</head>
<body>

<h2>Interview Schedule</h2>
<div class="message">Scheduled <?= $totalScheduled ?> interview slots using Round Robin.</div>

<?php if (mysqli_num_rows($result) > 0): ?>
<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Company</th>
            <th>Date</th>
            <th>Time</th>
            <th>Mode</th>
            <th>Location</th>
            <th>Status</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['student_name']) ?></td>
            <td><?= htmlspecialchars($row['company_name']) ?></td>
            <td><?= htmlspecialchars($row['interview_date']) ?></td>
            <td><?= htmlspecialchars($row['interview_time']) ?></td>
            <td><?= htmlspecialchars($row['mode']) ?></td>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['remarks']) ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align:center;">No interview schedules found.</p>
<?php endif; ?>

</body>
</html>
