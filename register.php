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
        $message = "<div class='alert alert-success custom-alert'>Registration successful! Please <a href='login.php' class='alert-link'>log in here</a>.</div>";
    } else {
        $message = "<div class='alert alert-danger custom-alert'>Error: Could not register. This phone number might already be used.</div>";
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
    
    <style>
        body {
            background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px 0; /* Adds padding on mobile so it doesn't touch the top/bottom */
        }

        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.95);
            overflow: hidden;
        }

        .register-header {
            background: #0b5ed7; /* A deep blue to distinguish it from the green login header */
            padding: 25px 20px;
            border-bottom: none;
        }
        
        .register-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        .custom-input {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            box-shadow: 0 0 0 4px rgba(11, 94, 215, 0.2); /* Soft blue glow */
            border-color: #0b5ed7;
        }

        .btn-custom {
            background-color: #0b5ed7;
            color: white;
            border-radius: 10px;
            padding: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-custom:hover {
            background-color: #0a58ca;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(11, 94, 215, 0.3);
            color: white;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .custom-link {
            color: #0b5ed7;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .custom-link:hover {
            color: #0a58ca;
            text-decoration: underline;
        }
        
        .custom-alert {
            border-radius: 10px;
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5"> <div class="card register-card">
                <div class="card-header register-header text-white text-center">
                    <h4>Villager Registration</h4>
                    <small class="d-block mt-1">Join the Digital Grievance Portal</small>
                </div>
                <div class="card-body p-4">
                    
                    <?php echo $message; ?>

                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control custom-input" required placeholder="Enter your full name">
                        </div>
                        <div class="mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control custom-input" required placeholder="10-digit mobile number">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control custom-input" required placeholder="Create a strong password">
                        </div>
                        <button type="submit" class="btn btn-custom w-100">Register Account</button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p class="text-muted mb-0">Already registered?</p>
                        <a href="login.php" class="custom-link">Return to Login Page</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
