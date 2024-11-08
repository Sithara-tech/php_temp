<?php
// Database connection
$servername = "localhost";
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password
$dbname = "emptemppage"; // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add new employee
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_employee'])) {
    $name = $_POST['name'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $email = $_POST['email'];
    $hire_date = $_POST['hire_date'];

    $sql = "INSERT INTO employees (name, position, department, email, hire_date) 
            VALUES ('$name', '$position', '$department', '$email', '$hire_date')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Delete employee
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM employees WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-container {
            margin-bottom: 20px;
        }
        form input, form button {
            margin: 5px 0;
            padding: 10px;
            width: 100%;
            box-sizing: border-box;
        }
        form button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Employee Management</h1>
        
        <!-- Add Employee Form -->
        <div class="form-container">
            <form method="post">
                <input type="text" name="name" placeholder="Name" required>
                <input type="text" name="position" placeholder="Position">
                <input type="text" name="department" placeholder="Department">
                <input type="email" name="email" placeholder="Email" required>
                <input type="date" name="hire_date" required>
                <button type="submit" name="add_employee">Add Employee</button>
            </form>
        </div>

        <!-- Display Employees -->
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Email</th>
                    <th>Hire Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM employees";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['name']) . "</td>
                                <td>" . htmlspecialchars($row['position']) . "</td>
                                <td>" . htmlspecialchars($row['department']) . "</td>
                                <td>" . htmlspecialchars($row['email']) . "</td>
                                <td>" . htmlspecialchars($row['hire_date']) . "</td>
                                <td>
                                    <a href='index.php?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this employee?')\">Delete</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No employees found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php $conn->close(); ?>
