<!doctype html>
	<html lang="en" data-bs-theme="auto">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			@stack('meta')
			@yield('csrf')
			<meta name="csrf-token" content="{{ csrf_token() }}" />

			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
			<link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.min.css" rel="stylesheet">

			<!-- Font Lato CSS -->
			<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap">

			<!--- Simplebar CSS --->
			<link rel="stylesheet" href="https://unpkg.com/simplebar@latest/dist/simplebar.css">

			<!-- Custom CSS -->
			<link href="{{ asset('assets/css/aruna-v3.css') }}" rel="stylesheet">
			<link href="{{ asset('assets/css/aruna-admin-v6.css?v=12313') }}" rel="stylesheet">
			<link href="{{ asset('assets/css/phoenix-cms.css') }}" rel="stylesheet">		

			<title>{{ env("APP_NAME", "LaraPhoex") }} | @yield('title')</title>

			<noscript>
				<style>
				/*
				 * Reinstate scrolling for non-JS clients
				 */
			
				.simplebar-content-wrapper 
				{
					overflow: auto;
				}
				</style>
			</noscript>

			<style>
				[data-bs-theme=light] 
				{
					body
					{
						background-color: #F4F5F7 !important;
					}
					
					.arv6-sidebar
					{
						color: var(--bs-body-color);
						background-color: var(--bs-body-bg);
					}

					.arv6-box
					{
						color: var(--bs-body-color);
						border: 1px var(--bs-border-color) solid;
						border-radius: var(--bs-border-radius);
						background-color: var(--bs-body-bg);
					}
				}

				[data-bs-theme=dark] 
				{
					body
					{
						color: var(--bs-body-color);
						background-color: var(--bs-body-bg);
					}

					.arv6-sidebar
					{
						color: var(--bs-body-color);
						border-right: var(--bs-secondary-bg);
						background-color: var(--bs-secondary-bg);
					}

					.arv6-sidebar .arv6-menu .list-group-item
					{
						background-color: var(--bs-secondary-bg);
					}

					.arv6-menu .arv6-title
					{
						color: var(--bs-dark-text-emphasis);
					}

					.arv6-box
					{
						color: var(--bs-body-color);
						border: 1px var(--bs-dark-border-subtle) solid;
						border-radius: var(--bs-border-radius);
						background-color: var(--bs-secondary-bg);
					}
				}
			</style>
		</head>
		
		<body>
			<!--- Ardev v6 Container Theme --->
			<div class="arv6-container">

				<!--- List Menu for Desktop View --->
				<div class="arv6-sidebar">
					<div class="arv6-brand d-flex align-items-center justify-content-center">
						CMS Laravel
					</div>

					<div class="arv6-menu px-2" data-simplebar>
						<div class="arv6-title row">
							<div class="col-auto fw-bold">
								All
							</div>

							<div class="col ps-0">
								<hr class="navbar-vertical-divider mb-0">
							</div>
						</div>

						<ul class="list-group list-group-flush">
							<a href="{{ url('/') }}" class="list-group-item list-group-item-action" target="_blank">
								<i class="fad fa-external-link fa-fw me-2"></i>
								Visit Site
							</a>

							<a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action">
								<i class="fad fa-tachometer-alt fa-fw me-2"></i>
								Dashboard
							</a>

							<a href="{{ url('app/user') }}" class="list-group-item list-group-item-action">
								<i class="fad fa-tachometer-alt fa-fw me-2"></i>
								Testing List Data
							</a>

							<a href="javascript:void(0)" class="list-group-item list-group-item-action collapsed" data-bs-toggle="collapse" data-bs-target="#CollapseManageArticles" role="button" aria-expanded="false" aria-controls="CollapseManageArticles">
								<span class="text-truncate"><i class="fad fa-newspaper fa-fw me-2"></i> Manage Articles</span>
							</a>

							<div class="multi-collapse list-group-sub collapse" id="CollapseManageArticles">
								<div class="list-group list-group-flush">
									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-list fa-fw me-2"></i> List of Event
									</a>

									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-plus fa-fw me-2"></i> Add New
									</a>

									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-folder fa-fw me-2"></i> Article Categories
									</a>

									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-swatchbook fa-fw me-2"></i> Layout
									</a>
								</div>
							</div>

							<a href="javascript:void(0)" class="list-group-item list-group-item-action collapsed" data-bs-toggle="collapse" data-bs-target="#CollapseManageEvent" role="button" aria-expanded="false" aria-controls="CollapseManageEvent">
								<span class="text-truncate"><i class="fad fa-newspaper fa-fw me-2"></i> Manage Event</span>
							</a>

							<div class="multi-collapse list-group-sub collapse" id="CollapseManageEvent">
								<div class="list-group list-group-flush">
									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-list fa-fw me-2"></i> List of Event
									</a>

									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-plus fa-fw me-2"></i> Add New
									</a>

									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-folder fa-fw me-2"></i> Article Categories
									</a>

									<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
										<i class="fad fa-swatchbook fa-fw me-2"></i> Layout
									</a>
								</div>
							</div>
						</ul>

						<div class="arv6-title row">
							<div class="col-auto fw-bold">
								Admin
							</div>

							<div class="col ps-0">
								<hr class="navbar-vertical-divider mb-0">
							</div>
						</div>

						<ul class="list-group list-group-flush">
							<a href="{{ url('/') }}" class="list-group-item list-group-item-action" target="_blank">
								<i class="fad fa-user-secret fa-fw me-2"></i>
								Admin Panel
							</a>
						</ul>
					</div>
				</div>
				<!--- End of List Menu for Desktop View --->

				<!--- List Menu for Mobile View --->
				<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
					<div class="offcanvas-header py-4">
						<div class="arv6-brand p-0 d-flex align-items-center justify-content-center h-auto">
							CMS Laravel
						</div>

						<button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					</div>

					<div class="offcanvas-body py-0">
						<div class="arv6-menu px-0 py-0" data-simplebar>
							<div class="arv6-title row">
								<div class="col-auto fw-bold">
									All
								</div>

								<div class="col ps-0">
									<hr class="navbar-vertical-divider mb-0">
								</div>
							</div>

							<ul class="list-group list-group-flush">
								<a href="{{ url('/') }}" class="list-group-item list-group-item-action" target="_blank">
									<i class="fad fa-external-link fa-fw me-2"></i>
									Visit Site
								</a>

								<a href="{{ url('dashboard') }}" class="list-group-item list-group-item-action">
									<i class="fad fa-tachometer-alt fa-fw me-2"></i>
									Dashboard
								</a>

								<a href="javascript:void(0)" class="list-group-item list-group-item-action collapsed" data-bs-toggle="collapse" data-bs-target="#CollapseManageArticles" role="button" aria-expanded="false" aria-controls="CollapseManageArticles">
									<span class="text-truncate"><i class="fad fa-newspaper fa-fw me-2"></i> Manage Articles</span>
								</a>

								<div class="multi-collapse list-group-sub collapse" id="CollapseManageArticles">
									<div class="list-group list-group-flush">
										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-list fa-fw me-2"></i> List of Event
										</a>

										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-plus fa-fw me-2"></i> Add New
										</a>

										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-folder fa-fw me-2"></i> Article Categories
										</a>

										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-swatchbook fa-fw me-2"></i> Layout
										</a>
									</div>
								</div>

								<a href="javascript:void(0)" class="list-group-item list-group-item-action collapsed" data-bs-toggle="collapse" data-bs-target="#CollapseManageEvent" role="button" aria-expanded="false" aria-controls="CollapseManageEvent">
									<span class="text-truncate"><i class="fad fa-newspaper fa-fw me-2"></i> Manage Event</span>
								</a>

								<div class="multi-collapse list-group-sub collapse" id="CollapseManageEvent">
									<div class="list-group list-group-flush">
										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-list fa-fw me-2"></i> List of Event
										</a>

										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-plus fa-fw me-2"></i> Add New
										</a>

										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-folder fa-fw me-2"></i> Article Categories
										</a>

										<a href="{{ url('#!') }}" class="list-group-item list-group-item-action ps-5">
											<i class="fad fa-swatchbook fa-fw me-2"></i> Layout
										</a>
									</div>
								</div>
							</ul>

							<div class="arv6-title row">
								<div class="col-auto fw-bold">
									Admin
								</div>

								<div class="col ps-0">
									<hr class="navbar-vertical-divider mb-0">
								</div>
							</div>

							<ul class="list-group list-group-flush">
								<a href="{{ url('/') }}" class="list-group-item list-group-item-action" target="_blank">
									<i class="fad fa-user-secret fa-fw me-2"></i>
									Admin Panel
								</a>
							</ul>
						</div>
					</div>
				</div>
				<!--- End of List Menu for Mobile View --->

				<!--- Main Side for Main Content --->
				<div class="arv6-main-side">
					<!--- Main Header Side --->
					<div class="arv6-header-side mb-4 navbar navbar-expand-lg navbar-light">
						<div class="container-fluid">
							<ul class="navbar-nav arv6-button-bars">
								<li class="nav-item dropdown">
									<a class="nav-link" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"><i class="far fa-bars fa-lg"></i></a>
								</li>
							</ul>

							<ul class="navbar-nav ms-auto">
								<li class="nav-item dropdown">
									<a href="javascript:void(0)" class="nav-link dropdown-toggle" aria-current="page" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-cog fa-lg"></i></a>

									<ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
										<li><a class="dropdown-item font-size-normal" href="{{ url('/') }}"><i class="fas fa-cog fa-fw me-1"></i> Account Settings</a></li>
										<li><a class="dropdown-item font-size-normal" href="{{ url('auth/logout') }}"><i class="fas fa-sign-out fa-fw me-1"></i> Logout</a></li>

										<li><a class="dropdown-item font-size-normal" href="javascript:void(0)" data-bs-theme-value="light" aria-pressed="true">Light Mode</a></li>
										<li><a class="dropdown-item font-size-normal" href="javascript:void(0)" data-bs-theme-value="dark" aria-pressed="false">Dark Mode</a></li>
									</ul>
								</li>
							</ul>
		  				</div>
		  			</div>
		  			<!--- End of Main Header Side --->

					<div class="arv6-content-side">
						@yield('content')
					</div>
				</div>
				<!--- End of Main Side for Main Content --->
			</div>

			<!--- Javascript Code Dark Mode --->
			<script type="text/javascript">
			(() => {
			  'use strict'

			  const getStoredTheme = () => localStorage.getItem('theme')
			  const setStoredTheme = theme => localStorage.setItem('theme', theme)

			  const getPreferredTheme = () => {
			    const storedTheme = getStoredTheme()
			    if (storedTheme) {
			      return storedTheme
			    }

			    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
			  }

			  const setTheme = theme => {
			    if (theme === 'auto') {
			      document.documentElement.setAttribute('data-bs-theme', (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'))
			    } else {
			      document.documentElement.setAttribute('data-bs-theme', theme)
			    }
			  }

			  setTheme(getPreferredTheme())

			  const showActiveTheme = (theme, focus = false) => {
			    const themeSwitcher = document.querySelector('#bd-theme')

			    if (!themeSwitcher) {
			      return
			    }

			    // const themeSwitcherText = document.querySelector('#bd-theme-text')
			    // const activeThemeIcon = document.querySelector('.theme-icon-active use')
			    const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
			    // const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')

			    document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
			      element.classList.remove('active')
			      element.setAttribute('aria-pressed', 'false')
			    })

			    btnToActive.classList.add('active')
			    btnToActive.setAttribute('aria-pressed', 'true')
			    // activeThemeIcon.setAttribute('href', svgOfActiveBtn)
			    const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
			    themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)

			    if (focus) {
			      themeSwitcher.focus()
			    }
			  }

			  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
			    const storedTheme = getStoredTheme()
			    if (storedTheme !== 'light' && storedTheme !== 'dark') {
			      setTheme(getPreferredTheme())
			    }
			  })

			  window.addEventListener('DOMContentLoaded', () => {
			    showActiveTheme(getPreferredTheme())

			    document.querySelectorAll('[data-bs-theme-value]')
			      .forEach(toggle => {
			        toggle.addEventListener('click', () => {
			          const theme = toggle.getAttribute('data-bs-theme-value')
			          setStoredTheme(theme)
			          setTheme(theme)
			          showActiveTheme(theme, true)
			        })
			      })
			  })
			})()
			</script>

			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
			<script src="{{ url('assets/plugins/fontawesome/5.15.3/js/all.min.js') }}"></script>

			<script src="https://unpkg.com/vue/dist/vue.global.prod.js"></script>

			<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
			<script src="https://unpkg.com/vuejs-paginate-next@latest/dist/vuejs-paginate-next.umd.js"></script>
			<script src="https://unpkg.com/vue-debounce@latest/dist/vue-debounce.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
			<script src="https://unpkg.com/vue-debounce@latest/dist/vue-debounce.min.js"></script>

			<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
			<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

			<script src="{{ url('assets/js/vuejs-v3-2024.js') }}"></script>

			@stack('js')
	</body>
</html>