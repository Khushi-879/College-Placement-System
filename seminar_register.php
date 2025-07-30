<?php
include 'db_connect.php';


$seminars = $conn->query("SELECT * FROM seminars")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seminar_id = $_POST['seminar_id'];
    $student_id = $_POST['student_id'];

    $stmt = $conn->prepare("INSERT INTO seminar_registrations (seminar_id, student_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $seminar_id, $student_id);
    $stmt->execute();
    $stmt->close();

    echo "Registered for seminar successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seminar Registration</title>
</head>
<body>
    <h2>Register for Seminar</h2>
    <form action="seminar_register.php" method="POST">
        <select name="seminar_id">
            <?php foreach ($seminars as $seminar): ?>
                <option value="<?php echo $seminar['id']; ?>"><?php echo $seminar['title']; ?> on <?php echo $seminar['date']; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="number" name="student_id" placeholder="Your Student ID" required>
        <button type="submit">Register</button>
    </form>
</body>
</html>
