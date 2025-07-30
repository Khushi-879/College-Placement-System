<?php
// round_robin_scheduler.php
include 'db_connect.php'; 

$company_id = isset($_POST['company_id']) ? (int)$_POST['company_id'] : 0;
$time_slot = isset($_POST['time_slot']) ? $_POST['time_slot'] : '09:00:00';
$intervalMinutes = isset($_POST['interval_min']) ? (int)$_POST['interval_min'] : 30;

if ($company_id === 0) {
    die("Error: company_id is required.");
}


$studentSql = "
    SELECT student_id
    FROM applications
    WHERE company_id = ? AND status = 'Applied'
    ORDER BY student_id
";
$stmt = $conn->prepare($studentSql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$studentsResult = $stmt->get_result();
$students = [];
while ($row = $studentsResult->fetch_assoc()) {
    $students[] = $row['student_id'];
}
$stmt->close();

if (count($students) === 0) {
    die("No students found with status 'Applied' for company ID $company_id.");
}


$dateSql = "SELECT interview_date FROM companies WHERE id = ?";
$stmt = $conn->prepare($dateSql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$stmt->bind_result($interview_date);
if (!$stmt->fetch()) {
    die("Interview date not found for company ID $company_id.");
}
$stmt->close();


$panelSql = "
    SELECT id
    FROM interviewers
    WHERE company_id = ?
    ORDER BY id
";
$stmt = $conn->prepare($panelSql);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$panelResult = $stmt->get_result();
$interviewers = [];
while ($row = $panelResult->fetch_assoc()) {
    $interviewers[] = $row['id'];
}
$stmt->close();

if (count($interviewers) === 0) {
    die("No interviewers found for company ID $company_id.");
}


$startTimes = [];
foreach ($interviewers as $id) {
    $startTimes[$id] = new DateTime("$interview_date $time_slot");
}


$insertSql = "
    INSERT INTO interview_slots (company_id, student_id, interviewer_id, slot_time)
    VALUES (?, ?, ?, ?)
";
$stmt = $conn->prepare($insertSql);
$conn->begin_transaction();

try {
    $numInterviewers = count($interviewers);

    foreach ($students as $i => $student_id) {
        $panelIndex = $i % $numInterviewers;
        $interviewer_id = $interviewers[$panelIndex];
        $slot_time = $startTimes[$interviewer_id]->format('Y-m-d H:i:s');

        $stmt->bind_param("iiis", $company_id, $student_id, $interviewer_id, $slot_time);
        if (!$stmt->execute()) {
            throw new Exception("Failed to insert slot for student ID $student_id.");
        }

        $startTimes[$interviewer_id]->modify("+{$intervalMinutes} minutes");
    }

    $conn->commit();
    echo "Interview slots created successfully for company ID $company_id.";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$stmt->close();
$conn->close();
?>
