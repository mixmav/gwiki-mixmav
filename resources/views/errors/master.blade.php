<!DOCTYPE html>
<html lang="en" dir="ltr">
	<head>
		<title>
            @yield('title')
        </title>

        <meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="csrf-token" content="{{ csrf_token() }}">
    	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    	<meta name="theme-color" content="#343434">
    	<link rel="shortcut icon" href="/images/branding/xhdpi.png?v=lOahIyaGhYt">

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{mix('/css/app.css')}}">

		<script type="text/javascript">
			$(document).ready(function(){
				var landingPageCanvas = $('canvas')[0];
				var context = landingPageCanvas.getContext('2d');

				var mouse = {
					x: undefined,
					y: undefined
				}
				var boundHeight = window.innerHeight;
				var boundWidth = document.body.clientWidth;

				$(window).on({
					'mousemove touchstart': function(event){
						mouse.x = event.pageX;
						mouse.y = event.pageY;
					},
					resize: function(){
						canvasInit();
					},
				});

				function Circle(x, y, dx, dy, radius, bg){
					this.x = x;
					this.y = y;
					this.dx = dx;
					this.dy = dy;
					this.radius = radius;
					this.defaultRadius = radius;
					this.defaultMaxRadius = (Math.random() * radius * boundWidth/50) + 20;
					this.bg = bg;

					this.draw = function(){
						context.beginPath();
						context.arc(this.x, this.y, this.radius, 0, Math.PI*2, false);
						context.fillStyle = this.bg;
						context.fill();
					}

					this.update = function(){
						if (this.x + this.radius > boundWidth || this.x - this.radius < 0) {
							this.dx = -this.dx;
						}

						if (this.y + this.radius > boundHeight || this.y - this.radius < 0) {
							this.dy = -this.dy;
						}

						if (this.x - mouse.x < 50 && this.x - mouse.x > -50 && this.y - mouse.y < 50 && this.y - mouse.y > -50) {
							if (this.radius < this.defaultMaxRadius) {
								this.radius += 5;
							}
						} else {
							if (this.radius > this.defaultRadius) {
								this.radius -= 1;
							}
						}

						this.x += this.dx;
						this.y += this.dy;

						this.draw();
					}
				}

				var circlesArray = [];

				function addNewCircle(){
					var radius = Math.random() + 1;
					var x = (Math.random() * (boundWidth - (radius*2))) + radius;
					var y = (Math.random() * (boundHeight - (radius*2))) + radius;
					var dx = (Math.random() - 0.5) * 3;
					var dy = (Math.random() - 0.5) * 3;

					var bg = '#';
					var bg_letters = 'BCDEF'.split('');
					for(var i = 0; i < 6; i++){
						bg += bg_letters[Math.floor(Math.random() * bg_letters.length)];
					}

					circlesArray.push(new Circle(x, y, dx, dy, radius, bg));
				}

				function animate(){
					landingPageAnimationFrameID = requestAnimationFrame(animate);
					context.clearRect(0, 0, boundWidth, boundHeight);

					for(var i = 0; i < circlesArray.length; i++){
						circlesArray[i].update();
					}
				}

				animate();

				function canvasInit(){
					boundHeight = window.innerHeight;
					boundWidth = document.body.clientWidth;

					landingPageCanvas.width = boundWidth;
					landingPageCanvas.height = boundHeight;

					context.clearRect(0, 0, boundWidth, boundHeight);
					cancelAnimationFrame(landingPageAnimationFrameID);

					circlesArray = [];

					for(var i = 0; i < (boundWidth/2); i++){
						addNewCircle();
					}

					animate();
				}
				canvasInit();
			});
		</script>

		<style>
			#landing-page{
				width: 100%;
				height: 100vh;
				background: rgba(0, 0, 0, 0.8);
				top: 0;
				left: 0;
				position: fixed;
			}

			canvas{
				position: absolute;
				top: 0;
				left: 0;
			}

			.center{
				position: absolute;
				left: 50%;
				top: 50%;
				text-align: center;
				transform: translate(-50%, -50%);
			}

			h1{
				color: white;
			}

			.a{
				color: #BD4089;
			}
			.a:hover{
				color: white;
			}
		</style>
	</head>
	<body>
		<div id="landing-page">
			<canvas class="canvas"></canvas>
			<div class="center">
				@yield('content')
				<a href="/" class="a">Home</a>
			</div>
		</div>
	</body>
</html>
