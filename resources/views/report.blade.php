@extends('layout.master')

@section('title')
	Report an issue or give feedback
@endsection

@section('custom-head')
	<link rel="stylesheet" href="{{mix('/css/report.css')}}">
@endsection

@section('main-content')
	<alerts></alerts>
	<top-bar></top-bar>
	<side-nav></side-nav>
	<settings></settings>

	<div class="container">
		<form action="/report" method="POST">
			<h1><i class="fa fa-people-carry"></i>Did Gwiki let you down?</h1>
			<h2><i class="fa fa-hand-lizard"></i>Tell me how, for good karma.</h2>

			@if ($errors->any())
				<div class="errors-container">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif

			@csrf
			<p class="label" style="margin-top: 20px"><i class="fa fa-user-secret"></i>Name <span>(optional)</span></p>
			<input value="{{old('name')}}" type="text" name="name" class="text-input" placeholder="Your name">

			<p class="label"><i class="fa fa-envelope"></i>Your email <span>(to receive a response)</span></p>
			<input value="{{old('email')}}" type="email" name="email" class="text-input" placeholder="Your email" required="">

			<p class="label"><i class="fa fa-link"></i>Wikipedia link used <span>(if any)</span></p>
			<input value="{{old('link')}}" type="text" name="link" class="text-input" placeholder="Leave blank if none">

			<p class="label"><i class="fa fa-comment"></i>Now, take a deep breath, and vent</p>
			<textarea name="message" placeholder="What went wrong or could be improved?" required="">{{old('message')}}</textarea>
			<p class="char-limit">Character limit: 2000</p>

			<button id="classChange" type="submit" class="btn h-ripple full-width"><i class="fa fa-hourglass-end"></i>Send</button>
		</form>
	</div>
@endsection

@section('post-script')
	<script type="text/javascript">
		$('form').submit(function(){
			$('#classChange').prop('disabled', true).addClass('disabled');
		});
		setTimeout(function(){
			if (getTheHash() == 'none') {
				setTimeout(function(){
					$('form').append('<input type="hidden" name="hash" value="' + getTheHash() + '">');
				}, 3000);
			} else {
				$('form').append('<input type="hidden" name="hash" value="' + getTheHash() + '">');
			}
		}, 1000)
	</script>

	@if(session()->has('issueSent'))
		@if(session('issueSent'))
			<script>
				showAlert("Thanks for that. I might get in touch if you entered an email.");
			</script>
		@endif
	@endif
@endsection
