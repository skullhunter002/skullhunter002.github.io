<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$database = "digital_library";

// Initialize variables for form data
$bookTitle = $bookAuthor = $bookDescription = "";
$uploadOk = 1;
$uploadMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create a connection to the database
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Handle form submission
    $bookTitle = $_POST['bookTitle'];
    $bookAuthor = $_POST['bookAuthor'];
    $bookDescription = $_POST['bookDescription'];

    // Upload PDF file
    $targetDir = "books/";
    $targetFile = $targetDir . basename($_FILES["bookCover"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check file type
    if($imageFileType != "pdf") {
        $uploadMessage = "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $uploadMessage = "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["bookCover"]["tmp_name"], $targetFile)) {
            $uploadMessage = "The file ". htmlspecialchars( basename( $_FILES["bookCover"]["name"])). " has been uploaded.";
            // Insert book data into database
            $sql = "INSERT INTO books (title, author, description, cover_image) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $bookTitle, $bookAuthor, $bookDescription, $targetFile);
            if ($stmt->execute()) {
                $uploadMessage .= " Book data inserted successfully.";
            } else {
                $uploadMessage .= " Error inserting book data: " . $conn->error;
            }
            $stmt->close();
        } else {
            $uploadMessage = "Sorry, there was an error uploading your file.";
        }
    }

    // Close database connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Digital Library</title>
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
        .dashboard-section {
            text-align: center;
            padding: 50px 20px;
        }
        .navbar-nav .nav-link {
            padding-right: 15px;
        }
        .add-book-section {
            text-align: center;
            padding: 50px 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Digital Library - Admin Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Manage Books</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Manage Users</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="dashboard-section">
        <h2>Welcome, Admin!</h2>
        <p>This is your dashboard. Manage books and users from here.</p>
    </div>

    <div class="add-book-section">
        <h2>Add a New Book</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="bookTitle">Book Title</label>
                <input type="text" class="form-control" id="bookTitle" name="bookTitle" required>
            </div>
            <div class="form-group">
                <label for="bookAuthor">Author</label>
                <input type="text" class="form-control" id="bookAuthor" name="bookAuthor" required>
            </div>
            <div class="form-group">
                <label for="bookDescription">Description</label>
                <textarea class="form-control" id="bookDescription" name="bookDescription" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="bookCover">Book Cover (PDF)</label>
                <input type="file" class="form-control-file" id="bookCover" name="bookCover" accept="application/pdf" required>
            </div>
            <button type="submit" class="btn btn-success">Add Book</button>
        </form>
        <div><?php echo $uploadMessage; ?></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
