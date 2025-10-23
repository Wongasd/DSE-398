<?php
// Include the database connection file
include_once("database/db.php");

// Fetch books data from the database
$queryBooks = "SELECT b.BookID, b.Title, b.Image, b.Quantity, 
                      CONCAT(a.FirstName, ' ', a.LastName) AS AuthorName 
               FROM books b 
               JOIN authors a ON b.AuthorID = a.AuthorID
               ORDER BY b.Title ASC 
               LIMIT 4"; // Limit to 4 featured books for display
$resultBooks = mysqli_query($conn, $queryBooks);

// Fetch genres from the database
$queryGenres = "SELECT * FROM genres ORDER BY GenreName ASC";
$resultGenres = mysqli_query($conn, $queryGenres);
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>All Books</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="css/normalize.css">
	<link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
	<link rel="stylesheet" type="text/css" href="css/vendor.css">
	<link rel="stylesheet" type="text/css" href="style.css">

</head>

		<header id="header">
			<div class="container-fluid">
				<div class="row">

					<div class="col-md-2">
						<div class="main-logo">
							<a href="index.php"><img src="images/main-logo.png" alt="logo"></a>
						</div>

					</div>

					<div class="col-md-10">

						<nav id="navbar">
							<div class="main-menu stellarnav">
								<ul class="menu-list">
									<li class="menu-item active"><a href="index.php">Home</a></li>

							</div>
						</nav>

					</div>

				</div>
			</div>
		</header>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

	<div id="header-wrap">

		<div class="top-content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-6">
						<div class="social-links">
							<ul>
								<!-- <li>
									<a href="#"><i class="icon icon-facebook"></i></a>
								</li>
								<li>
									<a href="#"><i class="icon icon-twitter"></i></a>
								</li>
								<li>
									<a href="#"><i class="icon icon-youtube-play"></i></a>
								</li>
								<li>
									<a href="#"><i class="icon icon-behance-square"></i></a>
								</li> -->
							</ul>
						</div> 
					</div>
					<div class="col-md-6">
    <div class="right-element">
        <a href="<?php echo isset($_SESSION['UserId']) ? 'users.php' : 'register.php'; ?>" class="user-account for-buy">
            <i class="icon icon-user"></i><span>Account</span>
        </a>
		
		<?php if (isset($_SESSION['UserId'])): ?>
                <a href="logout.php" class="btn btn-primary ml-2">
                    <i class="icon icon-logout"></i> Logout
                </a>
        <?php endif; ?>

        <div class="action-menu">
            <div class="search-bar">
                <a href="#" class="search-button search-toggle" data-selector="#header-wrap">
                    <i class="icon icon-search"></i>
                </a>
                <form role="search" method="get" class="search-box">
                    <input class="search-field text search-input" placeholder="Search" type="search">
                </form>
            </div>
        </div>
    </div>
</div>


				</div>
			</div>
		</div><!--top-content-->

	</div><!--header-wrap-->	

	<section id="popular-books" class="bookshelf">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="section-header align-center">
						<h2 class="section-title">All Books</h2>
					</div>

					<ul class="tabs">
						<li data-tab-target="#all-genre" class="active tab">All Genre</li>
						<?php while ($genre = mysqli_fetch_assoc($resultGenres)): ?>
							<li data-tab-target="#genre-<?php echo $genre['GenreID']; ?>" class="tab">
								<?php echo htmlspecialchars($genre['GenreName']); ?>
							</li>
						<?php endwhile; ?>
					</ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- All Books Section -->
            <div id="all-genre" data-tab-content class="active">
                <div class="row">
                    <?php
                    // Fetch all books
                    $queryAllBooks = "
                        SELECT b.BookID, b.Title, b.Image, 
                               CONCAT(a.FirstName, ' ', a.LastName) AS AuthorName 
                        FROM books b 
                        JOIN authors a ON b.AuthorID = a.AuthorID";
                    $resultAllBooks = mysqli_query($conn, $queryAllBooks);

                    // Check for database errors
                    if (!$resultAllBooks) {
                        die("Database query failed: " . mysqli_error($conn));
                    }

                    while ($book = mysqli_fetch_assoc($resultAllBooks)): ?>
                        <div class="col-md-3">
                            <div class="product-item">
                                <figure class="product-style">
                                    <img src="<?php echo htmlspecialchars($book['Image']); ?>" 
                                         alt="<?php echo htmlspecialchars($book['Title']); ?>" class="product-item">
                                    <button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to Cart</button>
                                </figure>
                                <figcaption>
                                    <h3><?php echo htmlspecialchars($book['Title']); ?></h3>
                                    <span><?php echo htmlspecialchars($book['AuthorName']); ?></span>
                                </figcaption>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Individual Genre Sections -->
            <?php
            // Reset pointer for genres
            mysqli_data_seek($resultGenres, 0);

            while ($genre = mysqli_fetch_assoc($resultGenres)): ?>
                <div id="genre-<?php echo $genre['GenreID']; ?>" data-tab-content>
                    <div class="row">
                        <?php
                        // Fetch books for this genre
                        $queryBooksByGenre = "
                            SELECT b.BookID, b.Title, b.Image, 
                                   CONCAT(a.FirstName, ' ', a.LastName) AS AuthorName 
                            FROM books b 
                            JOIN authors a ON b.AuthorID = a.AuthorID
                            WHERE b.GenreID = " . intval($genre['GenreID']);
                        $resultBooksByGenre = mysqli_query($conn, $queryBooksByGenre);

                        // Check for database errors
                        if (!$resultBooksByGenre) {
                            die("Database query failed: " . mysqli_error($conn));
                        }

                        while ($book = mysqli_fetch_assoc($resultBooksByGenre)): ?>
                            <div class="col-md-3">
                                <div class="product-item">
                                    <figure class="product-style">
                                        <img src="<?php echo htmlspecialchars($book['Image']); ?>" 
                                             alt="<?php echo htmlspecialchars($book['Title']); ?>" class="product-item">
                                        <button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to Cart</button>
                                    </figure>
                                    <figcaption>
                                        <h3><?php echo htmlspecialchars($book['Title']); ?></h3>
                                        <span><?php echo htmlspecialchars($book['AuthorName']); ?></span>
                                    </figcaption>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

				</div><!--inner-tabs-->

			</div>
		</div>
	</section>

	<footer id="footer">
		<div class="container">
			<div class="row">

				<div class="col-md-4">

					<div class="footer-item">
						<div class="company-brand">
							<img src="images/main-logo.png" alt="logo" class="footer-logo">
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sagittis sed ptibus liberolectus
								nonet psryroin. Amet sed lorem posuere sit iaculis amet, ac urna. Adipiscing fames
								semper erat ac in suspendisse iaculis.</p>
						</div>
					</div>

				</div>

				<div class="col-md-2">

					<div class="footer-menu">
						<h5>About Us</h5>
						<ul class="menu-list">
							<li class="menu-item">
								<a href="#">vision</a>
							</li>
							<li class="menu-item">
								<a href="#">articles </a>
							</li>
							<li class="menu-item">
								<a href="#">careers</a>
							</li>
							<li class="menu-item">
								<a href="#">service terms</a>
							</li>
							<li class="menu-item">
								<a href="#">donate</a>
							</li>
						</ul>
					</div>

				</div>
				<div class="col-md-2">

					<div class="footer-menu">
						<h5>Discover</h5>
						<ul class="menu-list">
							<li class="menu-item">
								<a href="index.php">Home</a>
							</li>
							<li class="menu-item">
								<a href="#">Books</a>
							</li>
							<li class="menu-item">
								<a href="#">Authors</a>
							</li>
							<li class="menu-item">
								<a href="#">Subjects</a>
							</li>
							<li class="menu-item">
								<a href="#">Advanced Search</a>
							</li>
						</ul>
					</div>

				</div>
				<div class="col-md-2">

					<div class="footer-menu">
						<h5>My account</h5>
						<ul class="menu-list">
							<li class="menu-item">
								<a href="#">Sign In</a>
							</li>
							<li class="menu-item">
								<a href="#">View Cart</a>
							</li>
							<li class="menu-item">
								<a href="#">My Wishtlist</a>
							</li>
							<li class="menu-item">
								<a href="#">Track My Order</a>
							</li>
						</ul>
					</div>

				</div>
				<div class="col-md-2">

					<div class="footer-menu">
						<h5>Help</h5>
						<ul class="menu-list">
							<li class="menu-item">
								<a href="#">Help center</a>
							</li>
							<li class="menu-item">
								<a href="#">Report a problem</a>
							</li>
							<li class="menu-item">
								<a href="#">Suggesting edits</a>
							</li>
							<li class="menu-item">
								<a href="#">Contact us</a>
							</li>
						</ul>
					</div>

				</div>

			</div>
			<!-- / row -->

		</div>
	</footer>

	<div id="footer-bottom">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="copyright">
						<div class="row">

							<div class="col-md-6">
								<p>© 2022 All rights reserved. Free HTML Template by <a
										href="https://www.templatesjungle.com/" target="_blank">TemplatesJungle</a></p>
							</div>

							<div class="col-md-6">
								<div class="social-links align-right">
									<ul>
										<li>
											<a href="#"><i class="icon icon-facebook"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon icon-twitter"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon icon-youtube-play"></i></a>
										</li>
										<li>
											<a href="#"><i class="icon icon-behance-square"></i></a>
										</li>
									</ul>
								</div>
							</div>

						</div>
					</div><!--grid-->

				</div><!--footer-bottom-content-->
			</div>
		</div>
	</div>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

	<script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('[data-tab-content]').forEach(c => c.classList.remove('active'));

                tab.classList.add('active');
                document.querySelector(tab.getAttribute('data-tab-target')).classList.add('active');
            });
        });
    </script>
	
</body>

</html>