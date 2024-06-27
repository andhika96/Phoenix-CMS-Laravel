<!doctype html>
	<html lang="en">
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

			<!-- Custom CSS -->
			<link href="{{ asset('assets/css/aruna-v3.css') }}" rel="stylesheet">
			<link href="{{ asset('assets/css/phoenix-cms.css') }}" rel="stylesheet">		

			@stack('css')

			<title>{{ env("APP_NAME", "LaraPhoex") }} | @yield('title')</title>
		</head>
		
		<body>
			@yield('content')
			
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
			<script src="{{ url('assets/plugins/fontawesome/5.15.3/js/all.min.js') }}"></script>

			<script src="{{ url('assets/plugins/vue/v3/3.4.27.js') }}"></script>
			<script src="{{ url('assets/plugins/axios/v1/1.7.2.js') }}"></script>
			
			<script src="https://unpkg.com/vuejs-paginate-next@latest/dist/vuejs-paginate-next.umd.js"></script>
			<script src="https://unpkg.com/vue-debounce@latest/dist/vue-debounce.min.js"></script>
			<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
			<script src="https://unpkg.com/vue-debounce@latest/dist/vue-debounce.min.js"></script>

			<script type="module"> 
				import blitzar from "https://cdn.jsdelivr.net/npm/blitzar@1.2.4/+esm"
			</script>

			<script src="https://code.jquery.com/jquery-3.7.1.slim.js" integrity="sha256-UgvvN8vBkgO0luPSUl2s8TIlOSYRoGFAX4jlCIm9Adc=" crossorigin="anonymous"></script>
			<script src="https://cdn.datatables.net/2.0.7/js/dataTables.min.js"></script>

			<script src="https://cdn.jsdelivr.net/npm/vue-draggable-plus@0.5.0/dist/vue-draggable-plus.iife.min.js"></script>

			<script src="{{ url('assets/js/vuejs-v3-2024.js') }}"></script>

			@stack('js')
	</body>
</html>