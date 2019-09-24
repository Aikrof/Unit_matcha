<!DOCTYPE HTML>
<html>
	<head>
		<title>Matcha</title>
		<meta charset="utf-8" />
		<meta name="csrf_token" content="{{ csrf_token() }}">
		<link rel="stylesheet" href="landingPage/css/main.css" />
		<link href='https://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
		<link rel="stylesheet" href="/landingPage/css/sign.css">
	</head>
	<body>

		<!-- Header -->
			<header id="header" class="hide">
				<div class="new_inner inner">
					<div class="content">
						<div class="maker">
							<h1>Matcha</h1>
							<h2>We make your love.</h2>
							<div class="a_sign"><a href="#sign" class="button big alt"><span>Sign Up/In</span></a></div>
							<a href="#" class="button big alt"><span>Back</span></a>
						</div>
					</div>
					<a href="#" class="button hidden"><span>Sign</span></a>
				</div>
			</header>

		<!-- Main -->
			<div id="main">
				<div class="inner">
					<div class="columns">

						<!-- Column 1 (horizontal, vertical, horizontal, vertical) -->
							<div class="image fit">
								<img src="landingPage/images/pic01.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic02.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic03.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic04.jpg" alt="" />
							</div>

						<!-- Column 2 (vertical, horizontal, vertical, horizontal) -->
							<div class="image fit">
								<img src="landingPage/images/pic06.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic05.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic08.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic07.jpg" alt="" />
							</div>

						<!-- Column 3 (horizontal, vertical, horizontal, vertical) -->
							<div class="image fit">
								<img src="landingPage/images/pic09.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic12.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic11.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic10.jpg" alt="" />
							</div>

						<!-- Column 4 (vertical, horizontal, vertical, horizontal) -->
							<div class="image fit">
								<img src="landingPage/images/pic13.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic14.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic15.jpg" alt="" />
							</div>
							<div class="image fit">
								<img src="landingPage/images/pic16.jpg" alt="" />
							</div>

					</div>
				</div>
			</div>
			<div id="sign"><a name="sign"></a>
				@include('auth.sign')
			</div>
		<!-- Footer -->
			<footer id="footer">
				<a href="#" class="info fa fa-info-circle"><span>About</span></a>
				<div class="inner">
					<div class="content">
						<h3>Matcha WEB PROJECT</h3>
						<p>App allowing two potential lovers to meet, from the registration to the final encounter.</p>
					</div>
					<div class="copyright">
						<h3>Follow me</h3>
						<ul class="icons">
							<li><a href="#" class="icon fa-twitter"><span class="label">Twitter</span></a></li>
							<li><a href="#" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
							<li><a href="#" class="icon fa-instagram"><span class="label">Instagram</span></a></li>
							<li><a href="#" class="icon fa-dribbble"><span class="label">Dribbble</span></a></li>
						</ul>
						&copy; UNIT 2019.
					</div>
				</div>
			</footer>

		<!-- Scripts -->
			<script src="landingPage/js/jquery.min.js"></script>
			<script src="landingPage/js/skel.min.js"></script>
			<script src="landingPage/js/util.js"></script>
			<script src="landingPage/js/main.js"></script>
			<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    		<script  src="/landingPage/js/sign.js"></script>
    		<script type="text/javascript" src="js/helper.js"></script>
    		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	</body>
</html>