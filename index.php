<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Library - Home</title>
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
        .welcome-section {
            text-align: center;
            padding: 50px 20px;
        }
        .library-section {
            text-align: center;
            padding: 50px 20px;
        }
        .navbar-nav .nav-link {
            padding-right: 15px;
        }
        .search-bar {
            margin-left: auto;
            margin-right: 15px;
        }
        .book-container {
            background-color: grey;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 15px;
            width: 300px;
            height: 400px; /* Fixed height to make it square */
            overflow: hidden;
            position: relative; /* Add position relative for positioning button */
        }
        .book {
            width: 100%;
            height: 100%;
        }
        .book img {
            max-width: 80%; /* Adjust as needed */
            max-height: 80%; /* Adjust as needed */
            height: auto;
            border-radius: 5px;
            transition: .5s ease;
            backface-visibility: hidden;
            object-fit: cover; /* Ensures the image covers the entire container */
        }
        .book-title {
            color: #000;
        }
        .middle {
          transition: .5s ease;
          opacity: 0;
          position: absolute;
          top: 50%;
          left: 50%;
          transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          text-align: center;
        }

        .book-container:hover .book img {
          opacity: 0.3;
        }

        .book-container:hover .middle {
          opacity: 1;
        }

        .text {
          background-color:rgb(88, 174, 192) ;
          color: white;
          font-size: 16px;
          padding: 16px 32px;
        }
        .read-button {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
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
            <form class="form-inline my-2 my-lg-0 search-bar" action="search.php" method="GET">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" name="query">
                <button class="btn btn-outline-dark my-2 my-sm-0" type="submit">Search</button>
            </form>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">User Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="adminlogin.php">Admin Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="welcome-section">
        <h1>Welcome to your Digital Library</h1>
        <p>Your own hub to gain Knowledge</p>
    </div>

    <div class="library-section">
        <h2>Digital Library Resources</h2>
        <p>Explore our extensive collection of digital resources.</p>
        <div class="row" id="bookContainer"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php
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

            // SQL query to fetch books from the database
            $sql = "SELECT * FROM books";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "fetchPdfAndRender('" . $row['cover_image'] . "', '" . $row['title'] . "', '" . $row['author'] . "');";
                }
            } else {
                echo "console.log('No books found');";
            }
            $conn->close();
            ?>

            function fetchPdfAndRender(pdfPath, title, author) {
                const loadingTask = pdfjsLib.getDocument(pdfPath);
                loadingTask.promise.then(function (pdf) {
                    pdf.getPage(1).then(function (page) {
                        const scale = 0.3; // Adjust scale here
                        const viewport = page.getViewport({ scale: scale });

                        // Prepare canvas using PDF page dimensions
                        const canvas = document.createElement('canvas');
                        const context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Render PDF page into canvas context
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.then(function () {
                            const image = new Image();
                            image.src = canvas.toDataURL('image/jpeg');
                            image.alt = title;
                            const bookContainer = document.getElementById('bookContainer');

                            const div = document.createElement('div');
                            div.className = 'col-md-4';
                            div.innerHTML = `<div class="book-container">
                                <img src="${image.src}" alt="${image.alt}">
                                <p class="book-title">${title}</p>
                                <p class="book-author">${author}</p>
                                <button class="btn btn-primary read-button">Read</button>
                            </div>`;
                            bookContainer.appendChild(div);
                        });
                    });
                }, function (reason) {
                    console.error('Error: ' + reason);
                });
            }
        });

        // Add event listener to the Read buttons
        document.addEventListener('click', function (event) {
            if (event.target.classList.contains('read-button')) {
                // Redirect to login.php when a Read button is clicked
                window.location.href = 'login.php';
            }
        });
    </script>
</body>
</html>
