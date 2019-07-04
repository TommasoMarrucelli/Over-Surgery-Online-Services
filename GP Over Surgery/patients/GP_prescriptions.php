<?php
session_start();
include '../includes/pdo_connect.php';

//check the prescription id 
if (isset($_POST['confirmbtn'])) {
	
	$user_id=$_SESSION['user_id'];
	$prescription_number= $_POST['prescription_number'];
	$_SESSION['prescription_number']=$prescription_number;

	$sqlCheckpres_date= "SELECT prescription_name, prescription_expiring_date, prescription_extendible FROM prescription WHERE prescription_id=?";
	$checkpres_date=$db->prepare($sqlCheckpres_date);
	$checkpres_date->bindParam(1, $prescription_number);
	$checkpres_date->execute();
	$row=$checkpres_date->fetch(PDO::FETCH_ASSOC);


	if (!empty($row)){
	
		$_SESSION['pres_date']= $row['prescription_expiring_date'];
		$pres_ext= $row['prescription_extendible'];
		$_SESSION['pres_ext']=$pres_ext;
		$_SESSION['pres_name']= $row['prescription_name'];

//check if the prescription selected is extendible
		if ($pres_ext == "n") {
			$error_noext= "This prescription is not extendible";
			$_SESSION['Error_noext']= $error_noext;
		}
	}
	else{
		$error_wrongnumb= "Enter a valid prescription number";
		$_SESSION['Error_wrongnumb'] = $error_wrongnumb;
	}
}

//extend the prescription
if (isset($_POST['extendbtn'])) {

	if (isset($_SESSION['prescription_number'])) {
		

		if ($_SESSION['pres_ext'] == 'y') {
			

		$extend_date= date('Y-m-d', strtotime($_SESSION['pres_date']. '+14 day'));
		$sqlExtend_pres = 'UPDATE prescription SET prescription_expiring_date=? WHERE prescription_id=?';
		$extend_pres=$db->prepare($sqlExtend_pres);
		$extend_pres->bindParam(1,$extend_date);
		$extend_pres->bindParam(2,$_SESSION['prescription_number']);
		$extend_pres->execute();

		$success_extend = "Prescription successfully extended";
		$_SESSION['Successext'] = $success_extend;

		$sec="3";
		header("Refresh:$sec");
		}
		else{
			$error_noext= "This prescription is not extendible";
			$_SESSION['Error_noext']= $error_noext;
		}
	}else{
		$error_nonumb='Type a prescription number and click on confirm to extend the prescription';
		$_SESSION['Error_nonumb']= $error_nonumb;
	}

}



?>
<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>Prescriptions- Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_prescriptions-stylesheet.css">
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
				<b>Prescriptions</b> 
			</h1>
		</div>     <!-- article end-->

		<div id="panelcent">
			<div id="conttable">
				<table id="table">
					<tr id="tr1">
						<th id="th1">Prescription number</th>
						<th id="th2">Name</th>
						<th id="th3">Expiring date</th>
					</tr>
						
						<?php  

							$user_id = $_SESSION['user_id'];
							// Fetch appointment details and dislpay those in a table					
							$sqlPres_details= 'SELECT prescription_id, prescription_name, prescription_expiring_date FROM prescription WHERE user_id = ?';
							$pres_details = $db-> prepare ($sqlPres_details);
							$pres_details->bindParam(1,$user_id);
							$pres_details->execute();
								
							while ($row = $pres_details->fetch(PDO::FETCH_ASSOC)){

								$pres_date_dym= date('d-m-Y',strtotime($row['prescription_expiring_date']));
								

								echo "<tr>";
								echo "<td>".$row['prescription_id']."</td>";
								
								
								echo "<td>".$row['prescription_name']."</td>";
							
								echo "<td>".$pres_date_dym."</td>";
								echo "</tr>";

								$_SESSION['prescription_expiring_date']=$row['prescription_expiring_date'];
							}
								

						?>

				</table>
			</div>
		


			<div >
				<div id="formcont">


							<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" id="presnumbform">
								<div class="form-group">
									<label class="control-label" for="presid">Prescription number:</label>
									 	<input class="form-control" type="text" name="prescription_number" placeholder="Insert number" id="presid" value="<?php if(isset($_SESSION['prescription_number'])){echo $_SESSION['prescription_number'];} else{echo "";} ?>">

								</div>	 				
									<button type="submit" class="btn btn-success" name="confirmbtn" value="confirm">
										Confim
									</button> 
								
							</form>


							<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" id="exdateform" >
								<div class="form-group">
									<label class="control-label" for="presname">Prescription:</label>
									 	<input class="form-control" type="text" name="prrescription_name"  id="presname" value="<?php if(isset($_SESSION['pres_name'])){echo $_SESSION['pres_name'];} else{echo "";} ?>">

								</div>	 				
									<button type="submit" class="btn btn-success" name="extendbtn" value="extend">
										Extend
									</button> 
								
							</form>


							<?php

							if (isset($_SESSION['Successext'])) {
							echo '<div id="success_extend">'.$_SESSION['Successext'].'</div>';
							unset($_SESSION['Successext']);
							unset($_SESSION['prescription_number']);
							unset($_SESSION['pres_date']);
							unset($_SESSION['pres_ext']);
							unset($_SESSION['pres_name']);
							}

							if (isset($_SESSION['Error_wrongnumb'])) {
							echo '<div id="error_wrongnumb">'.$_SESSION['Error_wrongnumb'].'</div>';
							unset($_SESSION['Error_wrongnumb']);
							unset($_SESSION['prescription_number']);
							unset($_SESSION['pres_date']);
							unset($_SESSION['pres_ext']);
							unset($_SESSION['pres_name']);
							}

							if (isset($_SESSION['Error_noext'])) {
							echo '<div id="error_noext">'.$_SESSION['Error_noext'].'</div>';
							unset($_SESSION['Error_noext']);
							unset($_SESSION['prescription_number']);
							unset($_SESSION['pres_date']);
							unset($_SESSION['pres_ext']);
							unset($_SESSION['pres_name']);
							}

							if (isset($_SESSION['Error_nonumb'])) {
							echo '<div id="error_nonumb">'.$_SESSION['Error_nonumb'].'</div>';
							unset($_SESSION['Error_nonumb']);
							unset($_SESSION['pres_date']);
							unset($_SESSION['pres_ext']);
							unset($_SESSION['pres_name']);
							}

						?>



			</div>
		</div>
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