<?php
session_start();
require 'db.php'; 

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone = $_POST['phone_number'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE phone_number = '$phone'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['full_name'] = $row['full_name'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "<div class='alert alert-danger custom-alert'>Incorrect password.</div>";
        }
    } else {
        $error = "<div class='alert alert-danger custom-alert'>No account found with that phone number.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Villager Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* Modern Gradient Background */
        body {
            background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center; /* Centers the login box vertically */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Styling the Login Card */
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.95); /* Slight glass effect */
            overflow: hidden; /* Keeps the header rounded */
        }

        /* Custom Header */
        .login-header {
            background: #198754; /* Bootstrap Success Green */
            padding: 25px 20px;
            border-bottom: none;
        }
        
        .login-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
        }

        /* Input Fields */
        .custom-input {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: all 0.3s ease;
        }

        .custom-input:focus {
            box-shadow: 0 0 0 4px rgba(25, 135, 84, 0.2); /* Soft green glow */
            border-color: #198754;
        }

        /* Login Button */
        .btn-custom {
            background-color: #198754;
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
            background-color: #146c43;
            transform: translateY(-2px); /* Lifts the button slightly */
            box-shadow: 0 8px 15px rgba(25, 135, 84, 0.3);
        }

        /* Small Tweaks */
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        
        .custom-link {
            color: #198754;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .custom-link:hover {
            color: #146c43;
            text-decoration: underline;
        }
        
        .custom-alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4"> <div class="card login-card">
                <div class="card-header login-header text-white text-center">
                    <h4>Villager Login</h4>
                    <small class="d-block mt-1">Village Grievance System</small>
                </div>
                <div class="card-body p-4"> <?php echo $error; ?>

                    <form action="login.php" method="POST">
                        <div class="mb-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_number" class="form-control custom-input" required placeholder="Enter your registered number">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control custom-input" required placeholder="Enter your password">
                        </div>
                        <button type="submit" class="btn btn-custom w-100 mt-2">Log In Securely</button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <a href="register.php" class="custom-link">Don't have an account? Register here.</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
