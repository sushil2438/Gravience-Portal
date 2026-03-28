<?php
session_start();
session_destroy(); // Destroys all session data
header("Location: login.php"); // Send them back to login
exit();
?>