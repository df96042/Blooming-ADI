<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" type="text/css" href="reset.css" />
	<link rel="stylesheet" type="text/css" href="style.css" />
	<link rel="stylesheet" type="text/css" href="media-queries.css" />
	
 	<script type="text/javascript" src="/home/alarm/mjpg-streamer-code/mjpg-streamer/www/jquery.js"></script>    
    <script type="text/javascript" src="/home/alarm/mjpg-streamer-code/mjpg-streamer/www/jquery.rotate.js"></script>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
	<link href='http://fonts.googleapis.com/css?family=Droid+Serif:700,400,400italic,700italic' rel='stylesheet' type='text/css'>
	
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<script type="text/javascript">
/* Copyright (C) 2007 Richard Atterer, richardⓒatterer.net
   This program is free software; you can redistribute it and/or modify it
   under the terms of the GNU General Public License, version 2. See the file
   COPYING for details. */
var imageNr = 0; // Serial number of current image
var finished = new Array(); // References to img objects which have finished downloading
var paused = false;
function createImageLayer() {
  var img = new Image();
  img.style.position = "absolute";
  img.style.zIndex = -1;
  img.onload = imageOnload;
  img.onclick = imageOnclick;
  img.width = 256;
  img.height = 192;
  img.src = "http://192.168.11.122:8080/?action=snapshot&n=" + (++imageNr);
  var webcam = document.getElementById("webcam");
  webcam.insertBefore(img, webcam.firstChild);
}
// Two layers are always present (except at the very beginning), to avoid flicker
function imageOnload() {
  this.style.zIndex = imageNr; // Image finished, bring to front!
  while (1 < finished.length) {
    var del = finished.shift(); // Delete old image(s) from document
    del.parentNode.removeChild(del);
  }
  finished.push(this);
  if (!paused) createImageLayer();
}
function imageOnclick() { // Clicking on the image will pause the stream
  paused = !paused;
  if (!paused) createImageLayer();
}
</script>
<body id="home" onload="createImageLayer();">
	<div id="wrapper">
		
		<header>
			<?php

				include_once('./index.php');
				$regid = $_REQUEST[regid];
			?>
				
				<h3>Stream :</h3>
		
			<p><div id="webcam" style="width:256px;height:192px"><noscript><img src="/?action=snapshot" width="256px" height="192px" /></noscript></div></p>
			</div> <!-- END Featured -->
			<div class="clearfix"></div>
			<hr/>
		</section>	
		
	</div> <!-- END Wrapper -->
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</body>
</html>
