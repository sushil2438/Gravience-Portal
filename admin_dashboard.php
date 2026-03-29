<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

$message = "";

// Handle Status & Remark Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];
    $admin_remark = $conn->real_escape_string($_POST['admin_remark']); // Safe input

    $update_sql = "UPDATE complaints SET status='$new_status', admin_remark='$admin_remark' WHERE id='$complaint_id'";
    if ($conn->query($update_sql) === TRUE) {
        $message = "<div class='alert alert-success'>Status and notification sent successfully!</div>";
    }
}

$sql = "SELECT complaints.*, users.full_name, users.phone_number 
        FROM complaints JOIN users ON complaints.user_id = users.id 
        ORDER BY complaints.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-danger mb-4">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">Authority Control Panel</a>
        <a href="admin_logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>

<div class="container-fluid px-4">
    <?php echo $message; ?>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Villager</th>
                            <th>Issue & Photo</th>
                            <th>Status</th>
                            <th>Send Notification / Update Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $badge = ($row['status'] == 'Pending') ? 'bg-warning text-dark' : 'bg-success';
                                
                                echo "<tr>";
                                echo "<td><strong>" . $row['full_name'] . "</strong><br>" . $row['phone_number'] . "</td>";
                                
                                echo "<td><strong>" . $row['category'] . "</strong>: " . $row['description'] . "<br>";
                                if ($row['photo_path']) {
                                    echo "<a href='" . $row['photo_path'] . "' target='_blank' class='btn btn-sm btn-outline-info mt-2'>View Evidence Photo</a>";
                                }
                                echo "</td>";

                                echo "<td><span class='badge " . $badge . "'>" . $row['status'] . "</span></td>";
                                
                                // Form to send notification and update status
                                echo "<td>
                                        <form action='admin_dashboard.php' method='POST'>
                                            <input type='hidden' name='complaint_id' value='" . $row['id'] . "'>
                                            <div class='input-group input-group-sm mb-2'>
                                                <select name='status' class='form-select' required>
                                                    <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                                    <option value='Resolved' " . ($row['status'] == 'Resolved' ? 'selected' : '') . ">Resolved</option>
                                                </select>
                                            </div>
                                            <div class='input-group input-group-sm mb-2'>
                                                <input type='text' name='admin_remark' class='form-control' placeholder='Type a notification message...' value='" . htmlspecialchars($row['admin_remark'] ?? '') . "'>
                                            </div>
                                            <button type='submit' name='update_status' class='btn btn-primary btn-sm w-100'>Update & Notify</button>
                                        </form>
                                      </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
