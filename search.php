<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Digital Library</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
            padding-top: 70px;
        }
        .navbar {
            background-color: #5a378e;
            color: #fff;
        }
        .navbar-dark .navbar-brand {
            color: #fff;
        }
        .search-results {
            padding: 20px;
        }
        .book-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .book-container img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .book-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
        }
        .book-author {
            font-size: 16px;
            margin-top: 5px;
        }
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand" href="#">Digital Library</a>
    </nav>

    <div class="container search-results">
        <h1>Search Results</h1>
        <div class="row">
            <?php
                // Simulated search results (replace with actual PHP code to fetch from database)
                $searchQuery = $_GET['query'];
                // Assuming you have a database connection established
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

                // Prepare and execute search query
                $sql = "SELECT * FROM books WHERE title LIKE '%$searchQuery%' OR author LIKE '%$searchQuery%'";
                $result = $conn->query($sql);

                // Display search results
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="col-md-4">';
                        echo '<div class="book-container">';
                        echo '<img src="' . $row["image"] . '" alt="' . $row["title"] . '">';
                        echo '<p class="book-title">' . $row["title"] . '</p>';
                        echo '<p class="book-author">' . $row["author"] . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-md-12">';
                    echo '<p>No results found.</p>';
                    echo '</div>';
                }

                // Close connection
                $conn->close();
            ?>
        </div>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
