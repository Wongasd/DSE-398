<?php 
$currentPage = basename($_SERVER['PHP_SELF']);

$Permission = isset($_SESSION['Permission']) ? $_SESSION['Permission'] : '';
?>

<div id="header-wrap">

<div class="top-content">
	<div class="container-fluid">
		<div class="row">

			<div class="col-md-6">
				<div class="social-links">
					<ul>
					</ul>
				</div> 
			</div>

			<div class="col-md-6">

				<div class="right-element">
					<a href="<?php echo isset($_SESSION['UserID']) ? 'account.php?UserID=' . $_SESSION['UserID'] : '#'; ?>"  class="user-account for-buy"
						<?php if (!isset($_SESSION['UserID'])): ?>
							onclick="alert('You haven\'t logged in'); window.location.href = 'login.php'; return false;"
						<?php endif; ?>
					>
						Account
					</a>

					<?php if (isset($_SESSION['UserID'])): ?>
							<a href="log_out.php?action=logout" class="user-account for-buy">
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
							<a href="index.php"><img style="width:125px;" src="images/logo.png" alt="logo"></a>
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
											<li><a href="all_books.php">All Books</a></li>	
											<li><a href="all_authors.php">All Authors</a></li>	
											<li><a href="all_publishers.php">All Publishers</a></li>	
											<?php if ($Permission == '1'){ ?>
											<li><a href="all_users.php">All Users</a></li>	
											<li><a href="add_publisher.php">Add Publishers</a></li>	
											<li><a href="add_author.php">Add Authors</a></li>
											<li><a href="add_users.php">Add Users</a></li>
											<li><a href="add_books.php">Add Books</a></li>
											<li><a href="borrow_list.php">Borrow History</a></li>		
											<li><a href="add_genre.php">Add Genre</a></li>	
											<?php }elseif($Permission == '3'){ ?>
											<li><a href="add_books.php">Add Books</a></li>
											<li><a href="add_genre.php">Add Genre</a></li>	
											<li><a href="add_publisher.php">Add Publishers</a></li>	
											<li><a href="add_author.php">Add Authors</a></li>
											<?php } ?>					
										</ul>

									</li>

									<?php if ($currentPage == 'index.php'): ?>
										<li class="menu-item"><a href="#featured-books" class="nav-link">Featured</a></li>
									<?php endif; ?>

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