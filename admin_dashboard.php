<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card h3 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="#">Dashboard</a>
        <a href="manage_std.php">Manage Students</a>
        <a href="add_companies.php">Manage Companies</a>
        <a href="#">Events</a>
        <a href="#">Logout</a>
    </div>

    <div class="main-content">
        <div class="card">
            <h3>Summary</h3>
            <p>Total Students: 120</p>
            <p>Total Companies: 15</p>
            <p>Total Applications: 87</p>
            <p>Eligible Students: 75</p>
        </div>

        <div class="card">
            <h3>Recent Applications</h3>
            <table>
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Company</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Rahul Verma</td>
                        <td>Infosys</td>
                        <td>Applied</td>
                        <td>2025-05-17</td>
                    </tr>
                    <tr>
                        <td>Anjali Mehta</td>
                        <td>Google</td>
                        <td>Shortlisted</td>
                        <td>2025-05-16</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="card">
            <h3>Upcoming Deadlines</h3>
            <ul>
                <li>Wipro - May 25, 2025</li>
                <li>TCS - May 28, 2025</li>
            </ul>
        </div>

    </div>

</body>
</html>
