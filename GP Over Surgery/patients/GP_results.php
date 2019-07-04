<?php
session_start();

include '../includes/pdo_connect.php';



?>

<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>Test Results- Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_results-stylesheet.css">
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
			<h1 id="h1cent">
				<b>Test Results</b> 
			</h1>

			<div class="container-fluid" id="panelcent">

			<div id="conttable">
				<table id="table">
					<tr id="tr1">
						<th id="th1">Test name</th>
						<th id="th2">Results</th>
					</tr>
						<?php  

							$user_id = $_SESSION['user_id'];
							// Fetch appointment details and dislpay those in a table					
							$sqlTest= 'SELECT * FROM test_results WHERE user_id=?';
							$test=$db->prepare($sqlTest);
							$test->bindParam(1,$_SESSION['user_id']);
							$test->execute();

							

								
							while ($row = $test->fetch(PDO::FETCH_ASSOC)){


								?>

								<tr>
									<td><?php echo $row['test_name'] ?></td>

									<td> 
										<div id="Nurseduty">
												<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#results" id="btnopen">Open</button>

												<!-- Modal -->
												<div id="results" class="modal fade" role="dialog">
												  <div class="modal-dialog modal-lg">

												    <!-- Modal content-->
												    <div class="modal-content" id="modalcontent">
												      <div class="modal-header">
												        <button type="button" class="close" data-dismiss="modal">&times;</button>
												        <h4 class="modal-title">Test Results</h4>
												      </div>
												      <div class="modal-body">
												        <object data="data:application/pdf;base64,<?php echo base64_encode($row['test_file']) ?>" type="application/pdf" style="height:90vh;width:80vh"> </object> 
												      </div>
												      <div class="modal-footer">
												        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												      </div>
												    </div>

												  </div>
												</div>
											</td>
										

								</tr>


							<?php
								}
							?>

				</table>
			</div>
			</div>
		</div>     <!-- article end-->

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