<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Signup digital Library</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #7f59b0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            text-align: center;
        }

        .card-container {
            perspective: 1000px;
        }
        .card {
            background-color: #f5f5f5;
            border: none;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            transform-style: preserve-3d;
            transform: rotateY(0deg);
            transition: transform 0.5s;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card.signup-card {
            transform: rotateY(0deg);
            display: none;
        }
        
        .rotate-card {
            transform: rotateY(180deg);
            transition: transform 0.5s ease;
        }
    </style>
</head>
<body>
    <header class="bg-purple text-white text-center py-5">
        <h1>Welcome to your digital Library</h1>
        <p>Your own hub to gain Knowledge</p>
    </header>
    <div class="container">
        <div class="card-container">
            <div class="card login-card">
                <h2>User Login</h2>
                <form action="login.php" method="POST">
                    <div class="form-group">
                        <label for="loginUsername">Username</label>
                        <input type="text" class="form-control" id="loginUsername" name="loginUsername" required>
                    </div>
                    <div class="form-group">
                        <label for="loginPassword">Password</label>
                        <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
                <p><a href="#" id="goToSignup">Don't have an account? Sign Up</a></p>
            </div>

            <div class="card signup-card">
                <h2>User Sign Up</h2>
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
                    <button type="submit" class="btn btn-success">Sign Up</button>
                </form>
                <p><a href="#" id="goToLogin">Already have an account? Login</a></p>
            </div>
            
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        const loginCard = document.querySelector('.login-card');
        const signupCard = document.querySelector('.signup-card');

        document.getElementById('goToSignup').addEventListener('click', function() {
            loginCard.style.transform = 'rotateY(180deg)';
            signupCard.style.transform = 'rotateY(0deg)';
            setTimeout(() => {
                loginCard.style.display = 'none';
                signupCard.style.display = 'block';
            }, 250);
        });

        document.getElementById('goToLogin').addEventListener('click', function() {
            loginCard.style.transform = 'rotateY(0deg)';
            signupCard.style.transform = 'rotateY(180deg)';
            setTimeout(() => {
                loginCard.style.display = 'block';
                signupCard.style.display = 'none';
            }, 250);
        });
    </script>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Database connection parameters
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "digital_library";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Handle signup
        if (isset($_POST['signupFullName']) && isset($_POST['signupEmail']) && isset($_POST['signupUsername']) && isset($_POST['signupPassword']) && isset($_POST['confirmPassword'])) {
            $fullName = $_POST['signupFullName'];
            $email = $_POST['signupEmail'];
            $username = $_POST['signupUsername'];
            $password = $_POST['signupPassword'];
            $confirmPassword = $_POST['confirmPassword'];

            // Check if passwords match
            if ($password !== $confirmPassword) {
                echo "<script>alert('Passwords do not match');</script>";
            } else {
                // Check if username already exists
                $checkUserQuery = "SELECT * FROM users WHERE username='$username'";
                $result = $conn->query($checkUserQuery);
                if ($result && $result->num_rows > 0) {
                    echo "<script>alert('Username already exists');</script>";
                } else {
                    // Insert new user into database
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $insertUserQuery = "INSERT INTO users (full_name, email, username, password) VALUES ('$fullName', '$email', '$username', '$hashedPassword')";
                    if ($conn->query($insertUserQuery) === TRUE) {
                        echo "<script>alert('User registered successfully');</script>";
                    } else {
                        echo "<script>alert('Error: User registration failed');</script>";
                    }
                }
            }
        }

        // Handle login
        if (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {
            $username = $_POST['loginUsername'];
            $password = $_POST['loginPassword'];

            // Fetch user data from database
            $getUserQuery = "SELECT * FROM users WHERE username='$username'";
            $result = $conn->query($getUserQuery);
            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    // Redirect to dashboard
                    header("Location: dashboard.php");
                    exit;
                } else {
                    echo "<script>alert('Invalid username or password');</script>";
                }
            } else {
                echo "<script>alert('Invalid username or password');</script>";
            }
        }

        // Close the database connection
        $conn->close();
    }
    ?>
</body>
</html>
