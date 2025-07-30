<?php
include 'db_connect.php';

// Handle new student addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $email = $_POST['email'];
    $cgpa = $_POST['cgpa'];

    $stmt = $conn->prepare("INSERT INTO students (name, roll_number, email, cgpa) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $roll_number, $email, $cgpa);
    $stmt->execute();
    $stmt->close();
    $success = "Student added successfully!";
}

// Handle delete student
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM students WHERE id = $id");
    header("Location: manage_std.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Students</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            background-color: #1e2a38;
            position: fixed;
            color: #fff;
            padding-top: 20px;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #ccc;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #2d3e50;
            color: #fff;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .card {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .action-buttons button {
            margin-right: 5px;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .edit-btn {
            background-color: #28a745;
            color: white;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
        }
        input, select {
            padding: 10px;
            margin-top: 8px;
            width: 100%;
            font-size: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        .success {
            color: green;
            margin-top: 10px;
            font-weight: bold;
        }
        .search-bar {
            margin-bottom: 15px;
        }
    </style>
    <script>
        function confirmDelete(id) {
            if (confirm("Are you sure you want to delete this student?")) {
                window.location.href = "manage_std.php?delete_id=" + id;
            }
        }

        function searchStudents() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("#studentTable tbody tr");
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const roll = row.cells[2]?.textContent.toLowerCase() || '';
                row.style.display = (name.includes(input) || roll.includes(input)) ? "" : "none";
            });
        }
    </script>
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_std.php">Manage Students</a>
    <a href="add_companies.php">Manage Companies</a>
   
    <a href="logout.php">Logout</a> <!-- ✅ Changed this line -->
</div>

<div class="main-content">
    <div class="card">
        <h2>Add New Student</h2>
        <?php if (!empty($success)) echo "<div class='success'>✅ $success</div>"; ?>
        <form method="POST">
            <input type="hidden" name="add_student" value="1">
            <label>Name</label>
            <input type="text" name="name" required>

            <label>Roll Number</label>
            <input type="text" name="roll_number" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>CGPA</label>
            <input type="number" step="0.01" name="cgpa" required>

            <button type="submit" class="edit-btn">Add Student</button>
        </form>
    </div>

    <div class="card">
        <h2>Student List</h2>
        <div class="search-bar">
            <input type="text" id="searchInput" onkeyup="searchStudents()" placeholder="🔍 Search by name or roll number">
        </div>
        <table id="studentTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
                while ($row = $result->fetch_assoc()):
                ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td class="action-buttons">
                        <button class="edit-btn" onclick="alert('Edit feature not implemented yet')">Edit</button>
                        <button class="delete-btn" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
