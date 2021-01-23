<div class="header-container">
	<header class="header navbar navbar-expand-sm">
		<a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>
		<div class="nav-logo align-self-center">
			<a class="navbar-brand" href="index.html"><img alt="logo" src="{{ url('/') }}/assets/img/90x90.jpg"> <span class="navbar-brand-name">CORK</span></a>
		</div>

		<ul class="navbar-item topbar-navigation">
			<!-- BEGIN TOPBAR -->
			<div class="topbar-nav header navbar" role="banner">
				<nav id="topbar">
					<ul class="navbar-nav theme-brand flex-row text-center">
						<li class="nav-item theme-logo">
							<a href="index.html">
								<img src="{{ url('/') }}/assets/img/90x90.jpg" class="navbar-logo" alt="logo">
							</a>
						</li>
						<li class="nav-item theme-text">
							<a href="index.html" class="nav-link"> Cape </a>
						</li>
					</ul>
					<ul class="list-unstyled menu-categories" id="topAccordion">
						<li class="menu active">
							<a href="#dashboard" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle autodroprown">
								<div class="">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home shadow-icons"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
									<span>Dashboard</span>
								</div>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
							</a>
						</li>

						<li class="menu single-menu">
							<a href="#app" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
								<div class="">
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
									<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-cpu shadow-icons"><rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect><rect x="9" y="9" width="6" height="6"></rect><line x1="9" y1="1" x2="9" y2="4"></line><line x1="15" y1="1" x2="15" y2="4"></line><line x1="9" y1="20" x2="9" y2="23"></line><line x1="15" y1="20" x2="15" y2="23"></line><line x1="20" y1="9" x2="23" y2="9"></line><line x1="20" y1="14" x2="23" y2="14"></line><line x1="1" y1="9" x2="4" y2="9"></line><line x1="1" y1="14" x2="4" y2="14"></line></svg>
									<span>Master Data</span>
								</div>
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
							</a>
							<ul class="collapse submenu list-unstyled animated fadeInUp" id="app" data-parent="#topAccordion">
								<li><a href="{{ url('/jabatan') }}">Jabatan</a></li>
								<li><a href="{{ url('/meja') }}">Meja</a></li>
								<li><a href="{{ url('/menu') }}">Menu</a></li>
								<li><a href="{{ url('/shift') }}">Shift</a></li>
								<li><a href="{{ url('/user') }}">User</a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
			<!-- END TOPBAR -->
		</ul>
		<ul class="navbar-item flex-row ml-auto"></ul>
		<ul class="navbar-item flex-row nav-dropdowns">
			<li class="nav-item dropdown notification-dropdown">
				<a href="javascript:void(0);" class="nav-link dropdown-toggle" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class="badge badge-success"></span>
				</a>
				<div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="notificationDropdown">
					<div class="notification-scroll">
						<div class="dropdown-item">
							<div class="media server-log">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6" y2="6"></line><line x1="6" y1="18" x2="6" y2="18"></line></svg>
								<div class="media-body">
									<div class="data-info">
										<h6 class="">Server Rebooted</h6>
										<p class="">45 min ago</p>
									</div>
									<div class="icon-status">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>

			<li class="nav-item dropdown user-profile-dropdown order-lg-0 order-1">
				<a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="user-profile-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<div class="media">
						<div class="media-body align-self-center">
								<h6 style="margin-bottom: .3rem !important;"><span>Hi,</span> {{ session('username') }}</h6>
						</div>
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg>
						<img src="{{ url('/') }}/assets/img/90x90.jpg" class="img-fluid" alt="admin-profile">
						
					</div>
				</a>
					<div class="dropdown-menu position-absolute animated fadeInUp" aria-labelledby="userProfileDropdown">
						<div class="user-profile-section">
							<!-- <div class="media mx-auto">
									<img src="{{ url('/') }}/assets/img/90x90.jpg" class="img-fluid mr-2" alt="avatar">
									<div class="media-body">
											<h5>Shaun Park</h5>
											<p>Project Leader</p>
									</div>
							</div> -->
						</div>
						<div class="dropdown-item">
							<a href="user_profile.html">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> <span>My Profile</span>
							</a>
						</div>
						<div class="dropdown-item">
							<a href="{{ url('logout') }}">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> <span>Log Out</span>
							</a>
						</div>
					</div>
			</li>
		</ul>
	</header>
</div>