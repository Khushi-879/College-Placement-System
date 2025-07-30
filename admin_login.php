<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, password FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($id, $name, $db_password);
    $stmt->fetch();

    if ($id && $password === $db_password) {
        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_name'] = $name;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .form-container {
            background-color: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
            font-size: 28px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
        }

        button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            margin-top: 15px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        @media screen and (max-width: 500px) {
            .form-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
