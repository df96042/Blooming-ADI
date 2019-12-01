<!DOCTYPE html>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=0.8, minimum-scale=0, user-scalable=no, target-densitydpi=medium-dpi" />
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>** Blooming ADI **</title>
	<link rel="stylesheet" type="text/css" href="reset.css" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="media-queries.css" />
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link href='http://fonts.googleapis.com/css?family=Droid+Serif:700,400,400italic,700italic' rel='stylesheet' type='text/css'>
	
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body id="home">
	<div id="wrapper">
		
		<header>
			<?php
				$regid = $_REQUEST[regid];
			?>
				
			<h1><img src="adi.png" width="380" height="380" align="center"></a></h1>
				<span style=" font-size:2.7em"> BLOOMING - ADI </span><br><br><br>
			
			<nav>
				<a href="index.php?regid=$regid">Home</a>
				<a href="room.php?regid=$regid">Room</a>
				<a href="test2.php?regid=$regid">Pluse</a> 
				<a href="emergency.php?regid=$regid">Pill</a> 
				<a href="stream.php?regid=$regid">Streaming</a>
				<div class="clearfix"></div>
				</nav>
		</header>
				
		<br/>
		<section id="main-content">
			<div id="featured">
				<h3> SMART CARE HOME PROJECT FOR AGING </h3>
				<p><img src="ambu.png" width="50" height="50"></p>
				<h3> Respond to emergency <br/> Manage Medication</h3>
			</div> <!-- END Featured -->
		
		</section>	
		<hr/>		
		
	</div> <!-- END Wrapper -->
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</body>
</html>
