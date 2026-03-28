<?php
session_start();
$error = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // HARDCODED ADMIN CREDENTIALS
    // Username: sarpanch
    // Password: password123
    if ($username === 'sarpanch' && $password === 'password123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $error = "<div class='alert alert-danger'>Invalid admin credentials.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Authority Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark"> <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 mt-5">
            <div class="card shadow">
                <div class="card-header bg-danger text-white text-center">
                    <h4>Admin / Sarpanch Login</h4>
                </div>
                <div class="card-body">
                    
                    <?php echo $error; ?>

                    <form action="admin_login.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Hint: sarpanch">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Hint: password123">
                        </div>
                        <button type="submit" class="btn btn-danger w-100">Login as Admin</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>