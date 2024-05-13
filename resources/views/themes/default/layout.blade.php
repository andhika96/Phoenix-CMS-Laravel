<!doctype html>
	<html lang="en">
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1">

			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

			<!-- Font Lato CSS -->
			<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,300;0,400;0,600;0,700;1,400&display=swap">

			<!-- Custom CSS -->
			<link href="{{ asset('assets/css/aruna-v3.css') }}" rel="stylesheet">
			<link href="{{ asset('assets/css/phoenix-cms.css') }}" rel="stylesheet">

			<title>Bootstrap Demo</title>			
		</head>
		
		<body>

			@yield('content')

			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
			<script src="{{ url('assets/plugins/fontawesome/5.15.3/js/all.min.js') }}"></script>

			<script src="https://unpkg.com/vue/dist/vue.global.prod.js"></script>
			<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
			<script src="https://unpkg.com/vuejs-paginate-next@latest/dist/vuejs-paginate-next.umd.js"></script>
			<script src="{{ url('assets/js/vuejs-v3-2024.js') }}"></script>

			@stack('js')
	</body>
</html>