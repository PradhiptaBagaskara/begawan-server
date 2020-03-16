<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Download Aplikasi</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<style type="text/css">


::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	/*border-bottom: 1px solid #D0D0D0;*/
	font-size: 19px;
	font-weight: normal;
	/*margin: 0 0 14px 0;*/
	padding: 15px 15px 0px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	padding-bottom: 20px;
	text-align: center;
	align-content: center;
	border: 1px solid #D0D0D0;
	box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
</head>
<body>
	<?php

// var_dump($hea);
// echo $hea;

 
// echo 'File is ' . $fileSizeKB . ' KB in size.';

?>
	<div id="container">
		<h1><?php echo "Download Aplikasi PT. Bengawan Polosoro"; ?></h1>
		<code>
		<h6><?=$outputName?></h6>
		<h6>versi: <?=$versionName?></h6>
		<h6><?=$size?></h6>
</code>
		<a href="<?=$downloadUrl?>" class="btn btn-lg btn-success">Download</a>
	</div>


	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>