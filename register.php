<?php
// 1. Connect to the database
require 'db.php'; 

$message = ""; // Variable to hold success/error messages

// 2. Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['full_name'];
    $phone = $_POST['phone_number'];
    
    // Encrypt the password for security (Examiners love this)
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    // 3. Write the SQL query to insert the data
    $sql = "INSERT INTO users (full_name, phone_number, password) VALUES ('$name', '$phone', '$password')";

    // 4. Execute the query and check if it worked
    if ($conn->query($sql) === TRUE) {
        $message = "<div class='alert alert-success'>Registration successful! Please log in.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: Could not register. This phone number might already be used.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villager Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Register a New Account</h4>
                </div>
                <div class="card-body">
                    
                    <?php echo $message; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required placeholder="Enter your full name">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control" required placeholder="10-digit mobile number">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Create a strong password">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>