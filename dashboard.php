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
        $message = "<div class='alert alert-success custom-alert'>Grievance submitted successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger custom-alert'>Error submitting grievance.</div>";
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            /* Same theme as login, but fixed so it covers the whole scrolling page */
            background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            background-attachment: fixed;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 40px;
        }

        /* Modern Navigation Bar */
        .custom-navbar {
            background-color: rgba(33, 37, 41, 0.95) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
        }
        
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
            color: #a8e063 !important; /* Green accent text */
        }

        /* Glass-effect Cards */
        .dashboard-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #fff;
            padding: 18px 20px;
            border-bottom: none;
        }
        
        .card-header-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: #fff;
            padding: 18px 20px;
            border-bottom: none;
        }

        .dashboard-card h5 {
            font-weight: 700;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            color: #333; /* Dark text for the warning header */
        }
        
        .card-header-primary h5 {
            color: #fff; /* Light text for the blue header */
        }

        /* Custom Inputs */
        .custom-input {
            border-radius: 10px;
            padding: 10px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            box-shadow: 0 0 0 4px rgba(253, 160, 133, 0.2);
            border-color: #fda085;
        }

        /* Custom Buttons */
        .btn-custom-warning {
            background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            color: #fff;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            color: #333;
        }

        .btn-custom-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(253, 160, 133, 0.4);
            color: #000;
        }

        /* Table Styling */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .table tbody td {
            vertical-align: middle;
        }

        .custom-alert {
            border-radius: 10px;
            font-weight: 500;
        }
        
        /* Thumbnail Hover Effect */
        .img-thumbnail {
            transition: transform 0.3s ease;
            border-radius: 8px;
        }
        .img-thumbnail:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark custom-navbar mb-5">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#"> Village Grievance Portal</a>
        <span class="navbar-text text-white">
            Welcome, <strong><?php echo htmlspecialchars($full_name); ?></strong> | 
            <a href="logout.php" class="text-danger fw-bold text-decoration-none ms-2 px-3 py-1 bg-white rounded-pill shadow-sm">Logout</a>
        </span>
    </div>
</nav>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-lg-4 col-md-5 mb-4">
            <div class="card dashboard-card">
                <div class="card-header card-header-warning">
                    <h5 class="mb-0">File a New Grievance</h5>
                </div>
                <div class="card-body p-4">
                    <?php echo $message; ?>
                    <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Issue Category</label>
                            <select name="category" class="form-select custom-input" required>
                                <option value="">Select an issue...</option>
                                <option value="Water Supply">Water Supply / Handpump</option>
                                <option value="Electricity">Electricity / Wiring</option>
                                <option value="Roads">Broken Roads / Potholes</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary">Detailed Description</label>
                            <textarea name="description" class="form-control custom-input" rows="4" required placeholder="Describe the problem and exact location..."></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">Upload Evidence (Photo)</label>
                            <input type="file" name="photo" class="form-control custom-input" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-custom-warning w-100">Submit to Authorities</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            <div class="card dashboard-card">
                <div class="card-header card-header-primary">
                    <h5 class="mb-0">My Notifications & History</h5>
                </div>
                <div class="card-body p-0"> <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">Date</th>
                                    <th>Issue</th>
                                    <th>Evidence</th>
                                    <th>Status</th>
                                    <th class="pe-4">Admin Notification/Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_history->num_rows > 0) {
                                    while($row = $result_history->fetch_assoc()) {
                                        $badge = ($row['status'] == 'Pending') ? 'bg-danger' : 'bg-success';
                                        
                                        echo "<tr>";
                                        echo "<td class='ps-4 text-muted'>" . date('d M Y', strtotime($row['created_at'])) . "</td>";
                                        echo "<td><strong class='text-dark'>" . $row['category'] . "</strong><br><small class='text-muted'>" . htmlspecialchars($row['description']) . "</small></td>";
                                        
                                        // Display image if it exists
                                        if ($row['photo_path']) {
                                            echo "<td><a href='" . $row['photo_path'] . "' target='_blank'><img src='" . $row['photo_path'] . "' width='60' height='60' style='object-fit: cover;' class='img-thumbnail shadow-sm'></a></td>";
                                        } else {
                                            echo "<td><span class='badge bg-secondary opacity-50'>No Photo</span></td>";
                                        }

                                        echo "<td><span class='badge " . $badge . " rounded-pill px-3 py-2 shadow-sm'>" . $row['status'] . "</span></td>";
                                        
                                        // Display Admin Notification
                                        if ($row['admin_remark']) {
                                            echo "<td class='pe-4 text-success fw-bold'><div class='p-2 bg-success bg-opacity-10 rounded'>" . htmlspecialchars($row['admin_remark']) . "</div></td>";
                                        } else {
                                            echo "<td class='pe-4 text-muted fst-italic'>Waiting for update...</td>";
                                        }
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-4 text-muted'>You haven't submitted any complaints yet.</td></tr>";
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
