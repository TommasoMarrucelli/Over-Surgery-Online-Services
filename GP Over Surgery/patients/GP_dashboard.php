<?php 
session_start();
 ?>

<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>Dashboard - Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_dashboard-stylesheet.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

		<div class="flex-container" id="header">		 <!-- header start -->
			<div style="flex-grow: 0" id="contimg">
					<img src="../Images/nhs.jpg" id="img">  <!--image-->
			</div>

			<div style="flex-grow: 10" id="conthead">
				<h1 id="h1head">Over Surgery Online Services </h1> <!-- page title-->
			</div>									
		</div>										<!-- header end -->									

			<nav class="navbar navbar" id="navbar"> <!--navbar start -->
		  	  <div class="container-fluid">
		    	<ul class="nav navbar-nav">
		    	  <li><a href="GP_dashboard.php">Home</a></li>
			      <li class="dropdown">
			      	<a class="dropdown-toggle" data-toggle="dropdown" href="#">Appointments
			      		<span class="caret"></span></a>
			      		<ul class="dropdown-menu" id="dropdown">
			      			<li><a href="GP_booking.php">Book your appointment</a></li>
			      			<li><a href="GP_my_appointment.php">My appointments</a></li>
			      			<li><a href="GP_all_gp.php">All GPs and Nurses availability</a></li>
			      		</ul>
			      	</li>
			      <li><a href="GP_prescriptions.php">Prescriptions</a></li>
			      <li><a href="GP_results.php">Test Results</a></li>
				  <li><a href="GP_my_profile.php"> My Profile</a></li>
			      <li><a href="GP_log_out.php"> Log out</a></li>
			     <li><div id="hello_user"><?php  echo 'Hi'.' ' .$_SESSION['username'];?></div></li>
			    </ul>
			  </div>
			</nav> <!--navbar end -->
	



	<div class="flex-container" id="central"> <!-- central  container start-->

		<div class="container-fluid" id="contcent"> <!-- article start-->
			<h1 id="h1"><b>Home</b></h1>
		</div>     <!-- article end-->

		<div class="panel panel-default" id="panelcent">   <!--central panel start -->
			<div class="panel-body" id="panelcentbody">	
				<div class="col-md-6" id="col1">

					<h2 id="h2">
				<b> &#9655; You can use the navigation menu above to: </b> <br>
					<ul id="ul_you_can">
						<li>Check the GPs or nurses’s availability;</li>
						<li>To book, change and cancel an appointment;</li>
						<li>To extend the prescriptions;</li>
						<li>To check the results of tests you have done;</li>
						<li>To chat online with our receptionists;</li>
					</ul>    
			</h2>
					<h3 id="h3">
						&#9655; Opening hours:  <br>
						<ul id= "ul_open">						
							<li>Mon-Sun  8.00-12.00 14.00-18.00</li>
						</ul>
					</h3>

				</div>

	<!-- google maps API-->

				<div class="col-md-6" id="col2">
					<div class="container-fluid" id="mapcont">   
					<iframe width="600" height="450" frameborder="0" style="border:0" id="map" src="https://www.google.com/maps/embed/v1/place?q=place_id:ChIJS7LAy5x12EcRGr9Z_Z4VzZY&key=AIzaSyA2mkUq73ROzdX8VsQRpBAtl6dDm23kdfY"></iframe>
					</div>
				</div>


			</div>
		</div>		<!-- central panel end -->
	</div>   <!-- central container end-->


<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/5adde9bc5f7cdf4f05338721/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>

</body>
</html>