<?php
// Include the database connection file
include_once("database/db.php");

// Fetch existing genres, authors, and publishers from the database
$queryGenres = "SELECT * FROM genres ORDER BY GenreName ASC";
$resultGenres = mysqli_query($conn, $queryGenres);

$queryAuthors = "SELECT * FROM authors ORDER BY FirstName, LastName ASC";
$resultAuthors = mysqli_query($conn, $queryAuthors);

$queryPublishers = "SELECT * FROM publishers ORDER BY PublisherName ASC";
$resultPublishers = mysqli_query($conn, $queryPublishers);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Title = trim($_POST['Title']);
    $AuthorOption = $_POST['AuthorOption'];
    $NewFirstName = trim($_POST['FirstName']);
    $NewLastName = trim($_POST['LastName']);
    $Author = ($AuthorOption === 'new') ? "$NewFirstName $NewLastName" : $_POST['Author'];

    $GenreOption = $_POST['GenreOption'];
    $NewGenre = trim($_POST['NewGenre']);
    $Genre = ($GenreOption === 'new') ? $NewGenre : $_POST['Genre'];

    $PublisherOption = $_POST['PublisherOption'];
    $NewPublisherName = trim($_POST['PublisherName']);
    $Publisher = ($PublisherOption === 'new') ? $NewPublisherName : $_POST['Publisher'];

    $PublishedYear = $_POST['PublishedYear'];
    $CopiesAvailable = trim($_POST['CopiesAvailable']);
    $Image = $_FILES['Image'];

    // Validate fields
    if (empty($Title) || empty($Author) || empty($Genre) || empty($Publisher)) {
        echo "<script>alert('Title, Author, Genre, and Publisher are required');</script>";
    } else {
        $AuthorID = null;
        $GenreID = null;
        $PublisherID = null;

        // Handle new author insertion if the "new" option is selected
        if ($AuthorOption === 'new') {
            // Check if author already exists
            $checkAuthorQuery = "SELECT * FROM authors WHERE FirstName = ? AND LastName = ?";
            $stmt = $conn->prepare($checkAuthorQuery);
            $stmt->bind_param("ss", $NewFirstName, $NewLastName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // Author doesn't exist, insert the new author
                $insertAuthorQuery = "INSERT INTO authors (FirstName, LastName) VALUES (?, ?)";
                $stmt = $conn->prepare($insertAuthorQuery);
                $stmt->bind_param("ss", $NewFirstName, $NewLastName);
                $stmt->execute();
                $AuthorID = $stmt->insert_id;
            } else {
                // Author exists, fetch the AuthorID
                $row = $result->fetch_assoc();
                $AuthorID = $row['AuthorID'];
            }
        } else {
            // Get AuthorID for selected existing author
            $AuthorID = $_POST['Author'];
        }

        // Handle new genre insertion if the "new" option is selected
        if ($GenreOption === 'new') {
            // Check if genre already exists
            $checkGenreQuery = "SELECT * FROM genres WHERE GenreName = ?";
            $stmt = $conn->prepare($checkGenreQuery);
            $stmt->bind_param("s", $NewGenre);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // Genre doesn't exist, insert the new genre
                $insertGenreQuery = "INSERT INTO genres (GenreName) VALUES (?)";
                $stmt = $conn->prepare($insertGenreQuery);
                $stmt->bind_param("s", $NewGenre);
                $stmt->execute();
                $GenreID = $stmt->insert_id;
            } else {
                // Genre exists, fetch the GenreID
                $row = $result->fetch_assoc();
                $GenreID = $row['GenreID'];
            }
        } else {
            // Get GenreID for selected existing genre
            $GenreID = $_POST['Genre'];
        }

        // Handle new publisher insertion if the "new" option is selected
        if ($PublisherOption === 'new') {
            // Check if publisher already exists
            $checkPublisherQuery = "SELECT * FROM publishers WHERE PublisherName = ?";
            $stmt = $conn->prepare($checkPublisherQuery);
            $stmt->bind_param("s", $NewPublisherName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 0) {
                // Publisher doesn't exist, insert the new publisher
                $insertPublisherQuery = "INSERT INTO publishers (PublisherName) VALUES (?)";
                $stmt = $conn->prepare($insertPublisherQuery);
                $stmt->bind_param("s", $NewPublisherName);
                $stmt->execute();
                $PublisherID = $stmt->insert_id;
            } else {
                // Publisher exists, fetch the PublisherID
                $row = $result->fetch_assoc();
                $PublisherID = $row['PublisherID'];
            }
        } else {
            // Get PublisherID for selected existing publisher
            $PublisherID = $_POST['Publisher'];
        }

        // Handle image upload
        $uploadDir = 'db_image/';
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileExtension = pathinfo($Image['name'], PATHINFO_EXTENSION);
        $imagePath = $uploadDir . uniqid() . '.' . $fileExtension;

        if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
            echo "<script>alert('Invalid image format. Only JPG, JPEG, PNG are allowed.');</script>";
        } elseif (move_uploaded_file($Image['tmp_name'], $imagePath)) {
            // Insert book data into the database
            $stmt = $conn->prepare("INSERT INTO books (Title, AuthorID, PublisherID, PublishedYear, GenreID, Quantity, Image) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisiss", $Title, $AuthorID, $PublisherID, $PublishedYear, $GenreID, $CopiesAvailable, $imagePath);

            if ($stmt->execute()) {
                echo "<script>alert('Book added successfully');</script>";
            } else {
                echo "<script>alert('Error: Could not add book.');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error uploading the image');</script>";
        }
    }
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book with Image</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/form-elements.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="shortcut icon" href="assets/ico/favicon.png">

<script>
    // Toggle visibility of new and existing fields for Author or Genre
    function toggleField(optionName, newFieldId, dropdownFieldId) {
        const option = document.querySelector(`input[name="${optionName}"]:checked`).value;
        const newField = document.getElementById(newFieldId); // New input field (div for multiple inputs)
        const dropdownField = document.getElementById(dropdownFieldId); // Dropdown field

        if (option === 'new') {
            newField.style.display = 'block';
            dropdownField.style.display = 'none';
        } else {
            newField.style.display = 'none';
            dropdownField.style.display = 'block';
        }
    }

    // Add specific handling for hiding 'Select Genre' dropdown when 'Enter new genre' is selected
    document.addEventListener('DOMContentLoaded', function() {
        const genreOption = document.querySelector('input[name="GenreOption"]');
        genreOption.addEventListener('change', function() {
            toggleField('GenreOption', 'newGenreField', 'GenreDropdown');
        });
    });
</script>

        <style>
            label {
                color: white; /* Set label text color to white */
            }
        </style>

</head>
<body>

<div class="top-content">
        <div class="inner-bg">

            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text">
                        <h2 class="mt-4" style="color: white;">Add a New Book</h2>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="form-box">
                            <div class="form-bottom">
                            <form method="POST" action="add_books.php" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="Title">Book Title:</label>
                                    <input type="text" class="form-control" name="Title" id="Title" required>
                                </div>

                                <div class="form-group">
                                    <label>Author:</label><br>
                                    <label>
                                        <input type="radio" name="AuthorOption" value="select" checked onclick="toggleField('AuthorOption', 'newAuthorField', 'AuthorDropdown')"> Select from existing
                                    </label>
                                    <label>
                                        <input type="radio" name="AuthorOption" value="new" onclick="toggleField('AuthorOption', 'newAuthorField', 'AuthorDropdown')"> Enter new author
                                    </label>
                                    <select class="form-control mt-2" name="Author" id="AuthorDropdown">
                                        <option value="">Select Author</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultAuthors)) { ?>
                                            <option value="<?php echo htmlspecialchars($row['FullName']); ?>"><?php echo htmlspecialchars($row['FullName']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <div id="newAuthorField" style="display: none; margin-top: 10px;">
                                        <input type="text" class="form-control mb-2" name="FirstName" placeholder="Enter First Name">
                                        <input type="text" class="form-control" name="LastName" placeholder="Enter Last Name">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Genre:</label><br>
                                    <label>
                                        <input type="radio" name="GenreOption" value="select" checked onclick="toggleField('GenreOption', 'newGenreField', 'GenreDropdown')"> Select from existing
                                    </label>
                                    <label>
                                        <input type="radio" name="GenreOption" value="new" onclick="toggleField('GenreOption', 'newGenreField', 'GenreDropdown')"> Enter new genre
                                    </label>
                                    <select class="form-control mt-2" name="Genre" id="GenreDropdown">
                                        <option value="">Select Genre</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultGenres)) { ?>
                                            <option value="<?php echo htmlspecialchars($row['GenreName']); ?>"><?php echo htmlspecialchars($row['GenreName']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="NewGenre" id="newGenreField" placeholder="Enter new genre" style="display: none;">
                                </div>

                                <div class="form-group">
                                    <label>Publisher:</label><br>
                                    <label>
                                        <input type="radio" name="PublisherOption" value="select" checked onclick="toggleField('PublisherOption', 'newPublisherField', 'PublisherDropdown')"> Select from existing
                                    </label>
                                    <label>
                                        <input type="radio" name="PublisherOption" value="new" onclick="toggleField('PublisherOption', 'newPublisherField', 'PublisherDropdown')"> Enter new publisher
                                    </label>
                                    <select class="form-control mt-2" name="Publisher" id="PublisherDropdown">
                                        <option value="">Select Publisher</option>
                                        <?php while ($row = mysqli_fetch_assoc($resultPublishers)) { ?>
                                            <option value="<?php echo htmlspecialchars($row['PublisherID']); ?>"><?php echo htmlspecialchars($row['PublisherName']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <input type="text" class="form-control mt-2" name="NewPublisherName" id="newPublisherField" placeholder="Enter new publisher name" style="display: none;">
                                </div>


                                <div class="form-group">
                                    <label for="PublishedYear">Published Year:</label>
                                    <input type="date" class="form-control" name="PublishedYear" id="PublishedYear" min="1000-01-01" max="<?php echo date('Y-m-d'); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="CopiesAvailable">Copies Available:</label>
                                    <input type="number" class="form-control" name="CopiesAvailable" id="CopiesAvailable" min="1" value="1">
                                </div>
                                <div class="form-group">
                                    <label for="Image">Upload Book Image:</label>
                                    <input type="file" class="form-control" name="Image" id="Image" accept=".jpg, .jpeg, .png, .gif" required>
                                </div>
                                    
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-6">
                                                <button type="submit" class="btn btn-primary btn-block">Add</button>
                                            </div>
                                            <div class="col-xs-6">
                                                <button type="button" class="btn btn-secondary btn-block" onclick="window.location.href='index.php'">Go back</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>

    <script src="assets/js/jquery-1.11.1.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
