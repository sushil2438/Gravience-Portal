<?php
session_start(); // This is crucial! It starts the memory for the logged-in user
require 'db.php'; 

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];
    
    // 1. Find the user in the database
    $sql = "SELECT * FROM users WHERE phone_number = '$phone'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 2. Verify the encrypted password
        if (password_verify($password, $row['password'])) {
            // Success! Store user details in the Session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            
            // Redirect them to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "<div class='alert alert-danger'>Incorrect password.</div>";
        }
    } else {
        $error = "<div class='alert alert-danger'>No account found with that phone number.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Villager Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-success text-white text-center">
                    <h4>Portal Login</h4>
                </div>
                <div class="card-body">
                    
                    <?php echo $error; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Login</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="register.php">Don't have an account? Register here.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>