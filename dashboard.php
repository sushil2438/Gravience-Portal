<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];
$message = "";

// Handle new complaint with PHOTO UPLOAD
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $description = $conn->real_escape_string($_POST['description']); 
    
    $photo_path = NULL;

    // Check if a file was uploaded without errors
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $target_dir = "uploads/";
        // Create a unique file name so images don't overwrite each other
        $file_extension = pathinfo($_FILES["photo"]["name"], PATHINFO_EXTENSION);
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Move the file from temporary storage to the uploads folder
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        }
    }

    $sql = "INSERT INTO complaints (user_id, category, description, photo_path) VALUES ('$user_id', '$category', '$description', '$photo_path')";

    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Grievance submitted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error submitting grievance.</div>";
    }
}

$sql_history = "SELECT * FROM complaints WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result_history = $conn->query($sql_history);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Villager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="#">Grievance Portal</a>
        <span class="navbar-text text-white">
            Welcome, <?php echo $full_name; ?> | <a href="logout.php" class="text-danger fw-bold text-decoration-none">Logout</a>
        </span>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">File a New Grievance</h5>
                </div>
                <div class="card-body">
                    <?php echo $message; ?>
                    <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Issue Category</label>
                            <select name="category" class="form-select" required>
                                <option value="">Select an issue...</option>
                                <option value="Water Supply">Water Supply / Handpump</option>
                                <option value="Electricity">Electricity / Wiring</option>
                                <option value="Roads">Broken Roads / Potholes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Detailed Description</label>
                            <textarea name="description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload Evidence (Photo)</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Submit to Authorities</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Notifications & History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Issue</th>
                                    <th>Photo</th>
                                    <th>Status</th>
                                    <th>Admin Notification/Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_history->num_rows > 0) {
                                    while($row = $result_history->fetch_assoc()) {
                                        $badge = ($row['status'] == 'Pending') ? 'bg-danger' : 'bg-success';
                                        
                                        echo "<tr>";
                                        echo "<td>" . date('d M', strtotime($row['created_at'])) . "</td>";
                                        echo "<td><strong>" . $row['category'] . "</strong><br><small>" . $row['description'] . "</small></td>";
                                        
                                        // Display image if it exists
                                        if ($row['photo_path']) {
                                            echo "<td><a href='" . $row['photo_path'] . "' target='_blank'><img src='" . $row['photo_path'] . "' width='50' class='img-thumbnail'></a></td>";
                                        } else {
                                            echo "<td>No Photo</td>";
                                        }

                                        echo "<td><span class='badge " . $badge . "'>" . $row['status'] . "</span></td>";
                                        
                                        // Display Admin Notification
                                        if ($row['admin_remark']) {
                                            echo "<td class='text-success fw-bold'>" . $row['admin_remark'] . "</td>";
                                        } else {
                                            echo "<td class='text-muted fst-italic'>Waiting for update...</td>";
                                        }
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
    </div>
</div>

</body>
</html>