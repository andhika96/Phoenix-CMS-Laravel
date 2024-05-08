@extends('themes.default.header')
    
<div id="app2">
	<div>
		<form action="{{ route('auth.login.validate') }}" method="post" @submit="login" ref="formHTML">
			 
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			<input type="text" name="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" v-model="responseMessage">
			<input type="password" name="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror">

			@csrf

			<input type="hidden" name="step" value="post">
			<input type="submit" class="btn btn-primary" value="Login">
		</form>
	</div>

	@{{ responseMessage }}

	<a href="#!" v-on:click="login">TESTING</a>
</div>

<div id="app">@{{ message }} asd</div>

@extends('themes.default.footer')