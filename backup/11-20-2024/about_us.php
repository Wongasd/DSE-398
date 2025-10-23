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
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Index</title>
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
									<li class="menu-item has-sub">
										<a href="#pages" class="nav-link">Pages</a>

										<ul>
											<li class="active"><a href="index.php">Home</a></li>
											<li><a href="about.php">About</a></li>
											<li><a href="index.html">Styles</a></li>
											<li><a href="index.html">Blog</a></li>
											<li><a href="index.html">Post Single</a></li>
											<li><a href="index.html">Our Store</a></li>
											<li><a href="index.html">Product Single</a></li>
											<li><a href="index.html">Contact</a></li>
											<li><a href="index.html">Thank You</a></li>
										</ul>

									</li>
									<li class="menu-item"><a href="#featured-books" class="nav-link">Featured</a></li>
									<li class="menu-item"><a href="#popular-books" class="nav-link">Popular</a></li>
									<li class="menu-item"><a href="#special-offer" class="nav-link">Offer</a></li>
									<li class="menu-item"><a href="#latest-blog" class="nav-link">Articles</a></li>
								</ul>

								<div class="hamburger">
									<span class="bar"></span>
									<span class="bar"></span>
									<span class="bar"></span>
								</div>

							</div>
						</nav>

					</div>

				</div>
			</div>
		</header>

	</div><!--header-wrap-->


		<div class="container">

                <h1>About Us</h1>
            <p>Welcome to our Library Management System! We aim to provide an easy-to-use platform for managing your library's book collections and user interactions efficiently.</p>
            
            <h2>Our Mission</h2>
            <p>To empower libraries with a seamless system to manage their resources and ensure readers have access to their favorite books anytime, anywhere.</p>

            <h2>What We Offer</h2>
            <ul>
                <li>A robust system for managing book inventories.</li>
                <li>Efficient handling of user accounts and borrowing history.</li>
                <li>An intuitive interface to make browsing books effortless.</li>
                <li>Secure management of user information and data.</li>
            </ul>

            <h2>Contact Us</h2>
            <p>Feel free to reach out to us for inquiries or support:</p>
            <p>Email: support@librarysystem.com</p>
            <p>Phone: +123 456 7890</p>
            
		</div>

    <?php include 'footer.php'; ?>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

</body>

</html>