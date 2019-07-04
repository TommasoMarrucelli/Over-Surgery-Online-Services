<?php 
	session_start();

	//connect to database
	include '../includes/pdo_connect.php';




if (isset($_POST['gpbtn'])) {
	$gp_id=$_POST['doctor'];
	$_SESSION['gp_id']=$gp_id;

	//convert the date selected in a format used by the db
	$date = $_POST['appointment_date'];;
	$_SESSION['appointment_date']=$date;
	$dbdate= date('Y-m-d', strtotime($date));
	//extract the day from the date
	$day = date('D', strtotime($date)); 
	
	//the id of doctors and nurses is valuate and connected to their names.
	switch ($gp_id) {
		case 1:
			$gp_name= "Dr. Bob Kelso";
			break;
		
		case 2:
			$gp_name= "Dr. Perry Cox";
			break;

		case 3:
			$gp_name= "Dr. John Dorian";
			break;

		case 4:
			$gp_name= "Dr. Elliot Reid";
			break;

		case 5:
			$gp_name= "Nurse Carla Espinosa";
			break;

		case 6:
			$gp_name= "Nurse Laverne Roberts";
			break;
				}

	$_SESSION['gp_name']=$gp_name;

	//Compare the chosen doctor's day off with the date selected. if they match display error
	$sqlCheck_day_off= "SELECT day_off FROM gps WHERE gp_id=?";
	$check_day_off=$db->prepare($sqlCheck_day_off);
	$check_day_off->bindParam(1,$gp_id);
	$check_day_off->execute();
	$day_off= $check_day_off->fetchColumn();		
	

		if ($day != $day_off) {
			//count the user's appointments number
				$sqlCheck_av= "SELECT COUNT(appointment_time) FROM appointments WHERE gp_id= ? AND appointment_date= ?";
				$check_av=$db->prepare($sqlCheck_av);
				$check_av->bindParam(1,$gp_id);
				$check_av->bindParam(2,$dbdate);
				$check_av->execute();
				$row_count = $check_av->fetchColumn();
				$_SESSION['appnumber']=$row_count;

			//select the doctor's time slots for the selected date
				$sqlTime_av= "SELECT appointment_time FROM appointments WHERE gp_id= ? AND appointment_date= ?";
				$time_av=$db->prepare($sqlTime_av);
				$time_av->bindParam(1,$gp_id);
				$time_av->bindParam(2,$dbdate);
				$time_av->execute();
				$result=$time_av->fetchColumn();
				$_SESSION['time']=$result;
	}
	else{
		$error_day_off= "We are sorry but"." ".$gp_name." "."is not on duty on the date selected.";
		$_SESSION['Error_day_off']=$error_day_off;
	}
}

//time values are stored in variables. They are used in HTML to display availability
$_8="8.00-9.00";
$_9="9.00-10.00";
$_10="10.00-11.00";
$_11="11.00-12.00";
$_13="13.00-14.00";
$_14="14.00-15.00";
$_15="15.00-16.00";
$_16="16.00-17.00";
$_17="17.00-18-00";




if (isset($_POST['bookingbtn'])) {		

	$user_id = $_SESSION['user_id'];

	$sqlCheck_app = "SELECT COUNT(appointment_id) FROM appointments WHERE user_id=?";
	$check_app = $db->prepare($sqlCheck_app);
	$check_app->bindParam(1,$user_id);
	$check_app->execute();
	$row_count = $check_app->fetchColumn();
	

	if ($row_count < 5) {

		if ((isset($_SESSION['gp_id'])) && isset($_SESSION['appointment_date']) && $_SESSION['appnumber']==0) {

			if (isset($_POST['appointment_time']) && isset($_POST['appointment_reason'])) {
				
			
			
				// if there are no problems, get the data from the front end

				//prepare the query
				$sqlInsert_app = "INSERT INTO appointments( gp_id, appointment_date, appointment_day, appointment_time, appointment_reason, user_id) VALUES
				(:gp_id,:appointment_date, :appointment_day, :appointment_time,:appointment_reason, :user_id)";
				
				$insert_app = $db->prepare($sqlInsert_app);
				// get the data from the front end and the session
				$gp_id=$_SESSION['gp_id'];
				$date = $_SESSION['appointment_date'];
				$day = date('D', strtotime($date));
				$time = $_POST['appointment_time'];
				$reason = $_POST['appointment_reason'];
				
				//execute the query
				$insert_app->execute(array(
				'gp_id' => $gp_id,
				'appointment_date' => $date,
				'appointment_day' => $day,
				'appointment_time' => $time,
				'appointment_reason' => $reason,
				'user_id' => $user_id,
				));
				$success_app = "Appointment successfully booked";
				$_SESSION['Success'] = $success_app;
			}
			else{
				$error_notime_reason="Select a slot time and specify the reason of the appointment";
				$_SESSION['Error_notime_reason']=$error_notime_reason;
			}
		}
		else{
					$error_nodoc_date= "Choose a doctor and select a date an an available time slot to book an appointment.";
					$_SESSION['Error_nodoc_date'] = $error_nodoc_date;
				}
			}
	  
	else{

		$error_5app ="You can book up to a maximum of 5 appointments at a time. Go to My Appointments page to delete or change the details of your appointments." ;
		$_SESSION['Error_5app'] = $error_5app;
	  }
} 

?>



<!DOCTYPE html>
<html>
<head>
	<title>Book an appointment - Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_booking-stylesheet.css">
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
		
		<h1 id="h1"><b>Book an appointment</b></h1>



		<div id="bookingcont">

			<p>Choose a doctor or a nurse and pick a date, then click on the choose button</p>

<!-- form to select doctor and date-->
			<form class="form-inline" id="gpform" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" >
  				<div class="form-group">
    				<label for="doctor" class="sr-only">Doctor</label>
					<select name="doctor" id="doctor" class="form-control" placeholder="Choose a doctor" required>
						<option value="" disabled selected> <?php if (!isset($_SESSION['gp_name'])) {
							echo "Choose a doctor";
						}else{
							echo $_SESSION['gp_name'];
						} ?></option>
						<option name="kelso" value="1">Dr. Bob Kelso</option>
						<option name="cox" value="2">Dr. Perry Cox</option>
						<option name="dorian" value="3">Dr. John Dorian</option>
						<option name="reid" value="4">Dr. Elliot Reid</option>
						<option name="espinosa" value="5">Nurse Carla Espinosa</option>
						<option name="roberts" value="6">Nurse Laverne Roberts</option>
					</select>
  				</div>

  				<div class="form-group">
					<label for="date">Date:</label>
						<input type="date" class="form-control" name="appointment_date" id="date" value="<?php if(isset($_SESSION['appointment_date'])){echo $_SESSION['appointment_date'];} else{echo "Select";} ?>" required>  				
				</div>
  
  				<button type="submit" class="btn btn-success" name="gpbtn" value="gpbtn" id="gpbtn">   <!-- log in button -->
				   	Choose 
				</button>
			</form>	


            <p id="p1">
				Now select an available time slot and specify the reason of the appointment 
				<br>
			</p>

<!--form to select time and reason of the appointment-->
			<form class="form-inline" id="gpform" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" > 

				<div class="form-group">
					<label for="time">Time:</label>
						<select class="form-control" name="appointment_time" id="time" value="appointment_time" required >										
							<option disabled selected>Select a time slot</option>				
							<option style="color:<?php if (isset($result)){echo ($result == $_8 ? 'red' : '#21c60d');} else {echo "black";} ?>">8.00-9.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_9 ? 'red' : '#21c60d');} else {echo "black";} ?>">9.00-10.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_10 ? 'red' : '#21c60d');} else {echo "black";} ?>">10.00-11.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_11 ? 'red' : '#21c60d');} else {echo "black";} ?>" >11.00-12.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_13 ? 'red' : '#21c60d');} else {echo "black";} ?>" >13.00-14.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_14 ? 'red' : '#21c60d');} else {echo "black";} ?>" >14.00-15.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_15 ? 'red' : '#21c60d');} else {echo "black";} ?>" >15.00-16.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_16 ? 'red' : '#21c60d');} else {echo "black";} ?>" >16.00-17.00</option>
							<option style="color:<?php if (isset($result)){echo ($result == $_17 ? 'red' : '#21c60d');} else {echo "black";} ?>" >17.00-18.00</option>
						</select>  				
				</div>

				<div class="form-group"> 
					<label for="reason">Appointment Reason:</label>
						<select class="form-control" name="appointment_reason" id="reason" value="appointment_reason" required>   <!-- reason picker -->
							<option>All routine GP appointments</option>
							<option>Flu immunisation clinics</option>
							<option>blood tests</option>
						</select>  				
				</div>
  
  				<button type="submit" class="btn btn-success mb-2" name="bookingbtn" value="bookingbtn" id="bookingbtn">   <!-- log in button -->
					   	Book  
				</button>


<!-- seccess and error messages-->
				<?php

					if (isset($_SESSION['Success'])) {
						echo '<div id="success_app">'.$_SESSION['Success'].'</div>';
						unset($_SESSION['Success']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appnumber']);
						unset($_SESSION['time']);
					}


					if (isset($_SESSION['Error_nodoc_date'])) {
						echo '<div id="error_nodoc_date">'.$_SESSION['Error_nodoc_date'].'</div>';
	        			unset($_SESSION['Error_nodoc_date']);
	        			unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appnumber']);
						unset($_SESSION['time']);
					}

					if (isset($_SESSION['Error_notime_reason'])) {
						echo '<div id="error_notime_reason">'.$_SESSION['Error_notime_reason'].'</div>';
	        			unset($_SESSION['Error_notime_reason']);
	        			unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appnumber']);
						unset($_SESSION['time']);
					}



					

					if (isset($_SESSION['Error_day_off'])) {
						echo '<div id="error_day_off">'.$_SESSION['Error_day_off'].'</div>';
						unset($_SESSION['Error_day_off']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appnumber']);
						unset($_SESSION['time']);
					}

					if (isset($_SESSION['Error_5app'])) {
						echo '<div id="error_5app">'.$_SESSION['Error_5app'].'</div>';
	        			unset($_SESSION['Error_5app']);
	        			unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appnumber']);
						unset($_SESSION['time']);
					}


					?>	

			</form>	
			
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



