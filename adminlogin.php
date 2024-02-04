<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Digital Library</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #7f59b0;
            color: #fff;
            min-height: 100vh;
            padding-top: 70px;
        }
        .navbar {
            background-color: #5a378e;
        }
        .navbar-dark .navbar-brand {
            color: #fff;
        }
        .navbar-dark .navbar-nav .nav-link {
            color: #fff;
        }
        .navbar-dark .navbar-toggler-icon {
            background-color: #fff;
        }
        .login-section {
            text-align: center;
            padding: 50px 20px;
        }
        .navbar-nav .nav-link {
            padding-right: 15px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Digital Library</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Library</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">User Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Admin Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="login-section">
        <h2>Admin Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="form-group">
                <label for="adminUsername">Username</label>
                <input type="text" class="form-control" id="adminUsername" name="adminUsername" required>
            </div>
            <div class="form-group">
                <label for="adminPassword">Password</label>
                <input type="password" class="form-control" id="adminPassword" name="adminPassword" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>

        <?php
        // Step 1: Database connection details
        $servername = "localhost";
        $username = "root"; // Replace with your database username
        $password = ""; // Replace with your database password
        $database = "digital_library";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Step 2: Create a connection to the database
            $conn = new mysqli($servername, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Step 3: Handle form submission
            $adminUsername = $_POST['adminUsername'];
            $adminPassword = $_POST['adminPassword'];

            // Step 4: Validate admin credentials against the database
            $sql = "SELECT * FROM admins WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $adminUsername);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                if (password_verify($adminPassword, $row['password'])) {
                    // Admin found, redirect to dashboard or perform desired actions
                    header("Location: admindashboard.php");
                    exit;
                }
            }

            // Admin not found or password incorrect, display error message
            echo '<div class="alert alert-danger" role="alert">Invalid username or password</div>';

            // Close database connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
