<!DOCTYPE html>
<html lang="en">
<head>
	<title>PT. BEGAWAN POLOSORO</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/vendor/countdowntime/flipclock.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="<?=base_url()?>assets/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	
	<div class="bg-img1 size1 overlay1 p-b-35 p-l-15 p-r-15" style="background-image: url('assets/images/bg01.jpg');">
		<div class="flex-col-c p-t-160 p-b-215 respon1">
	<!-- 		<div class="wrappic1">
				<a href="#">
					<img src="images/icons/logo.png" alt="IMG">
				</a>
			</div> -->

			<h3 class="l1-txt1 txt-center p-t-30 p-b-100">
				UNDER MAINTENANCE
			</h3>

			<div class="cd100"></div>

		</div>

		<!--  -->
		<!-- <div class="flex-w flex-c-m p-b-35">
			<a href="#" class="size3 flex-c-m how-social trans-04 m-r-3 m-l-3 m-b-5">
				<i class="fa fa-facebook"></i>
			</a>

			<a href="#" class="size3 flex-c-m how-social trans-04 m-r-3 m-l-3 m-b-5">
				<i class="fa fa-twitter"></i>
			</a>

			<a href="#" class="size3 flex-c-m how-social trans-04 m-r-3 m-l-3 m-b-5">
				<i class="fa fa-youtube-play"></i>
			</a>
		</div> -->
	</div>



	

<!--===============================================================================================-->	
	<script src="<?=base_url()?>assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="<?=base_url()?>assets/vendor/bootstrap/js/popper.js"></script>
	<script src="<?=base_url()?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="<?=base_url()?>assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="<?=base_url()?>assets/vendor/countdowntime/flipclock.min.js"></script>
	<script src="<?=base_url()?>assets/vendor/countdowntime/moment.min.js"></script>
	<script src="<?=base_url()?>assets/vendor/countdowntime/moment-timezone.min.js"></script>
	<script src="<?=base_url()?>assets/vendor/countdowntime/moment-timezone-with-data.min.js"></script>
	<script src="<?=base_url()?>assets/vendor/countdowntime/countdowntime.js"></script>
	<script>
		
		$('.cd100').countdown100({
			/*Set Endtime here*/
			/*Endtime must be > current time*/
			endtimeYear: 0,
			endtimeMonth: 0,
			endtimeDate: 35,
			endtimeHours: 18,
			endtimeMinutes: 0,
			endtimeSeconds: 0,
			timeZone: "" 
			// ex:  timeZone: "America/New_York"
			//go to " http://momentjs.com/timezone/ " to get timezone
		});

		
	</script>
<!--===============================================================================================-->
	<script src="<?=base_url()?>assets/vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="<?=base_url()?>assets/js/main.js"></script>

</body>
</html>