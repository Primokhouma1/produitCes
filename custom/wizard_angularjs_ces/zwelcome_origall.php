<?php include 'header.php'; ?>

		<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark" aria-label="Main navigation">
			<div class="container-fluid">
				<a class="navbar-brand" href="#">Offcanvas navbar</a>
				<button class="navbar-toggler p-0 border-0" type="button" id="navbarSideCollapse" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="#">Dashboard</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Notifications</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Profile</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#">Switch account</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-expanded="false">Settings</a>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="#">Action</a></li>
								<li><a class="dropdown-item" href="#">Another action</a></li>
								<li><a class="dropdown-item" href="#">Something else here</a></li>
							</ul>
						</li>
					</ul>
					<form class="d-flex" role="search">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
						<button class="btn btn-outline-success" type="submit">Search</button>
					</form>
				</div>
			</div>
		</nav>

		<div class="nav-scroller bg-body shadow-sm">
			<nav class="nav" aria-label="Secondary navigation">
				<a class="nav-link active" aria-current="page" href="#">Dashboard</a>
				<a class="nav-link" href="#">
					Friends
					<span class="badge text-bg-light rounded-pill align-text-bottom">27</span>
				</a>
				<a class="nav-link" href="#">Explore</a>
				<a class="nav-link" href="#">Suggestions</a>
				<a class="nav-link" href="#">Link</a>
				<a class="nav-link" href="#">Link</a>
				<a class="nav-link" href="#">Link</a>
				<a class="nav-link" href="#">Link</a>
				<a class="nav-link" href="#">Link</a>
			</nav>
		</div>

		<main class="container">
			<div class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm">
				<img class="me-3" src="assets/brand/bootstrap-logo-white.svg" alt="" width="48" height="38">
				<div class="lh-1">
					<h1 class="h6 mb-0 text-white lh-1">Bootstrap</h1>
					<small>Since 2011</small>
				</div>
			</div>

			<div class="my-3 p-3 bg-body rounded shadow-sm">
				<h6 class="border-bottom pb-2 mb-0">Recent updates</h6>
				<div class="d-flex text-muted pt-3">
					<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

					<p class="pb-3 mb-0 small lh-sm border-bottom">
						<strong class="d-block text-gray-dark">@username</strong>
						Some representative placeholder content, with some information about this user. Imagine this being some sort of status update, perhaps?
					</p>
				</div>
				<div class="d-flex text-muted pt-3">
					<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#e83e8c"/><text x="50%" y="50%" fill="#e83e8c" dy=".3em">32x32</text></svg>

					<p class="pb-3 mb-0 small lh-sm border-bottom">
						<strong class="d-block text-gray-dark">@username</strong>
						Some more representative placeholder content, related to this other user. Another status update, perhaps.
					</p>
				</div>
				<div class="d-flex text-muted pt-3">
					<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#6f42c1"/><text x="50%" y="50%" fill="#6f42c1" dy=".3em">32x32</text></svg>

					<p class="pb-3 mb-0 small lh-sm border-bottom">
						<strong class="d-block text-gray-dark">@username</strong>
						This user also gets some representative placeholder content. Maybe they did something interesting, and you really want to highlight this in the recent updates.
					</p>
				</div>
				<small class="d-block text-end mt-3">
					<a href="#">All updates</a>
				</small>
			</div>

			<div class="my-3 p-3 bg-body rounded shadow-sm">
				<h6 class="border-bottom pb-2 mb-0">Suggestions</h6>
				<div class="d-flex text-muted pt-3">
					<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

					<div class="pb-3 mb-0 small lh-sm border-bottom w-100">
						<div class="d-flex justify-content-between">
							<strong class="text-gray-dark">Full Name</strong>
							<a href="#">Follow</a>
						</div>
						<span class="d-block">@username</span>
					</div>
				</div>
				<div class="d-flex text-muted pt-3">
					<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

					<div class="pb-3 mb-0 small lh-sm border-bottom w-100">
						<div class="d-flex justify-content-between">
							<strong class="text-gray-dark">Full Name</strong>
							<a href="#">Follow</a>
						</div>
						<span class="d-block">@username</span>
					</div>
				</div>
				<div class="d-flex text-muted pt-3">
					<svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>

					<div class="pb-3 mb-0 small lh-sm border-bottom w-100">
						<div class="d-flex justify-content-between">
							<strong class="text-gray-dark">Full Name</strong>
							<a href="#">Follow</a>
						</div>
						<span class="d-block">@username</span>
					</div>
				</div>
				<small class="d-block text-end mt-3">
					<a href="#">All suggestions</a>
				</small>
			</div>
		</main>
		
<?php include 'footer.php';