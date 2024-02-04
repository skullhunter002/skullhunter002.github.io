<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="signupFullName">Full Name</label>
                <input type="text" class="form-control" id="signupFullName" name="signupFullName" required>
            </div>
            <div class="form-group">
                <label for="signupEmail">Email</label>
                <input type="email" class="form-control" id="signupEmail" name="signupEmail" required>
            </div>
            <div class="form-group">
                <label for="signupUsername">Username</label>
                <input type="text" class="form-control" id="signupUsername" name="signupUsername" required>
            </div>
            <div class="form-group">
                <label for="signupPassword">Password</label>
                <input type="password" class="form-control" id="signupPassword" name="signupPassword" required>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
        </form>
    </div>
</body>
</html>

<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $fullName = $_POST['signupFullName'];
    $email = $_POST['signupEmail'];
    $username = $_POST['signupUsername'];
    $password = $_POST['signupPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validation (e.g., check if passwords match, if username is available, etc.)
    if ($password != $confirmPassword) {
        echo "Passwords do not match";
        exit();
    }

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Username already exists";
        exit();
    }

    $sql = "INSERT INTO users (full_name, email, username, password) VALUES ('$fullName', '$email', '$username', '$password')";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['username'] = $username;
        $_SESSION['fullName'] = $fullName;
        header("Location: udashboard.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
