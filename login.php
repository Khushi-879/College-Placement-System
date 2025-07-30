<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['user'] = $_POST['username'];
    header("Location: dashboard.php");
}
?>
<form method="post">
    <input type="text" name="username" placeholder="Username" required>
    <button type="submit">Login</button>
</form>
