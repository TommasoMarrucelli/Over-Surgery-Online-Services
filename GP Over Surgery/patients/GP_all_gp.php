<?php 
session_start();
?>

<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>All GPs and Nurses availability - Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_all_gp-stylesheet.css">
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

		<div class="container-fluid" id="articlecont"> <!-- article start-->
			<h1 id="h1cent">
				<b>All GPs and Nurses availability </b> 
			</h1>
		</div>     <!-- article end-->

		<div class="container-fluid" id="contcent">   <!--central panel start -->
			
			<div id="GPduty">
				<!-- Trigger the modal with a button -->
				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#kelso">Dr. Bob Kelso</button>

				<!-- Modal -->
				<div id="kelso"" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Dr. Bob Kelso</h4>
				      </div>
				      <div class="modal-body">
				        <ul> Working hours:
				        	<li>
				        		Mon-Sat : 8.00-12.00  13.00-14.00
				        	</li>
				        	
				        </ul>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>

				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#cox">Dr. Perry Cox</button>

				<!-- Modal -->
				<div id="cox" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Dr. Perry Cox</h4>
				      </div>
				      <div class="modal-body">
				        <ul> Working hours:
				        	<li>
				        		Mon-Sat : 8.00-12.00  13.00-18.00
				        	</li>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>

				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#dorian">Dr. John Dorian</button>

				<!-- Modal -->
				<div id="dorian" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Dr. John Dorian</h4>
				      </div>
				      <div class="modal-body">
				       <ul> Working hours:
				        	<li>
				        		Mon-Fry : 8.00-12.00  13.00-18.00
				        	</li>
				        	<li>
				        		Sun : 8.00-12.00  13.00-18.00
				        	</li>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>

				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#reid">Dr. Elliot Reid</button>

				<!-- Modal -->
				<div id="reid" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Dr. Elliot Reid</h4>
				      </div>
				      <div class="modal-body">
				        <ul> Working hours:
				        	<li>
				        		Mon-Fry : 8.00-12.00  13.00-18.00
				        	</li>
				        	<li>
				        		Sun : 8.00-12.00  13.00-18.00
				        	</li>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>
			</div>
			

			<div id="Nurseduty">
				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#espinosa">Nurse Carla Espinosa</button>

				<!-- Modal -->
				<div id="espinosa" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Nurse Carla Espinosa</h4>
				      </div>
				      <div class="modal-body">
				        <ul> Working hours:
				        	<li>
				        		Mon-Sat : 8.00-12.00  13.00-18.00
				        	</li>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>


				<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#roberts">Nurse Laverne Roberts</button>

				<!-- Modal -->
				<div id="roberts" class="modal fade" role="dialog">
				  <div class="modal-dialog">

				    <!-- Modal content-->
				    <div class="modal-content">
				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal">&times;</button>
				        <h4 class="modal-title">Nurse Laverne Roberts</h4>
				      </div>
				      <div class="modal-body">
				        <ul> Working hours:
				        	<li>
				        		Mon-Fry : 8.00-12.00  13.00-18.00
				        	</li>
				        	<li>
				        		Sun : 8.00-12.00  13.00-18.00
				        	</li>
				      </div>
				      <div class="modal-footer">
				        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				      </div>
				    </div>

				  </div>
				</div>	

			</div>
		</div>   <!-- central container end-->

		

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