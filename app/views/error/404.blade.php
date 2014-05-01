<!DOCTYPE html>
<html lang="en">
<style type="text/css">

</style>
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
				bIS Project - Error 404: Page not found
		</title>
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<meta name="description" content="" />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->

		<style>
			body {
				padding: 0;
				margin: 0;
				font-family: arial;
			}
			.jumbotron {
			  padding: 0;
			  margin-bottom: 30px;
			  font-size: 21px;
			  font-weight: 200;
			  line-height: 2.1428571435;
			  color: #fff;
			  background-color: #cc0000;
			}
			.jumbotron h1 {
			  line-height: 1;
			  color: inherit;
			}
			.jumbotron p {
			  line-height: 1.4;
			}
			.container .jumbotron {
			  border-radius: 6px;
			}
			@media screen and (min-width: 768px) {
			  .jumbotron {
			    padding-top: 48px;
			    padding-bottom: 48px;
			  }
			  .container .jumbotron {
			    padding-left: 60px;
			    padding-right: 60px;
			  }
			  .jumbotron h1 {
			    font-size: 63px;
			  }
			}
			
			.jumbotron {
			  text-align: center;
			  -webkit-box-shadow: inset 0 1px 3px rgba(0,0,0,0.1), 0 0 8px rgba(0,0,0,0.6);
			  box-shadow: inset 0 1px 3px rgba(0,0,0,0.1), 0 0 8px rgba(0,0,0,0.6);
			  background-image: url("/assets/img/vaquitas07s.png");
			  padding: 30px;
			  letter-spacing: .05em;
			  text-shadow: 1px 1px 2px rgba(0,0,0,0.45);
			}
			.jumbotron h1 {
			  margin: 0;
			}
			.jumbotron .lang {
			  position: absolute;
			  right: 10%;
			}
			body {
			  padding-bottom: 70px;
			}
			.container.body {
				width: 600px;
				margin: 0 auto;
			}
			footer {
			  position: absolute;
			  width: 100%;
			  top: 100%;
			  left: 0;
			  margin-top: -50px;
			  height: 50px;
			  background: #FFECBE;
			  padding-top: 14px;
			  color: #999999;
			  position: fixed;
			  z-index: 10;
			}
			footer .stats > div {
			  margin-right: 20px;
			}
			footer .stats > div .label:hover {
			  background-color: #999999 !important;
			}
			footer .stats > div .label-success:hover {
			  background-color: #5CB85C !important;
			}
		</style>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<!-- <link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://bis.trialog.ch/assets/ico/apple-touch-icon-144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://bis.trialog.ch/assets/ico/apple-touch-icon-114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://bis.trialog.ch/assets/ico/apple-touch-icon-72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="http://bis.trialog.ch/assets/ico/apple-touch-icon-57-precomposed.png"> -->
		<link rel="shortcut icon" href="http://bis.trialog.ch/assets/ico/favicon.ico">
	</head>
	<body>
		<!-- Main -->
					
	<div class="jumbotron" >
		<div class="container">
			<h1>bIS</h1>
			<big>Begleitendes Informationssystem</big></div>
    </div>

	<div class="container">
		<div class="row">
			<div class="container body">			
			<?php $messages = array('We need a map.', 'I think we\'re lost.', 'We took a wrong turn.'); ?>

			<h1><?php echo $messages[mt_rand(0, 2)]; ?></h1>

			<h2>Server Error: 404 (Not Found)</h2>

			<hr>

			<h3>What does this mean?</h3>

			<p>
				We couldn't find the page you requested on our servers. We're really sorry
				about that. It's our fault, not yours. We'll work hard to get this page
				back online as soon as possible.
			</p>

			<p>
				Perhaps you would like to go to our <a href="{{{ URL::to('/') }}}">home page</a>?
			</p>			</div>
		</div>
	</div>

<footer id="footer">
	<div class="container">
	</div>
</footer>
</body>
</html>