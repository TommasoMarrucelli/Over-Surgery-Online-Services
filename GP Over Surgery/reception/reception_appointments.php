<?php 
	session_start();

	//connect to database
	include '../includes/pdo_connect.php';

	//check patient id by username
	if (isset($_POST['pat_uname_btn'])) {

		$patient_username= $_POST['patient_username'];
		$_SESSION['patient_username']=$patient_username;
		
		$sqlCheck_pat_id= "SELECT user_id FROM users WHERE username = ?";
		$check_pat_id=$db->prepare($sqlCheck_pat_id);
		$check_pat_id->bindParam(1,$patient_username);		
		$check_pat_id->execute();
		$pat_id= $check_pat_id->fetchColumn();


		if (!empty($pat_id)) {		
			$_SESSION['pat_id']=$pat_id;	
		}
		else{
			$error_wrongpatid = "Enter a valid appointment number";
			$_SESSION['Error_wrongpatid'] = $error_wrongpatid;
		}
	}	

//BOOKING 
// Determine which doctor has been selected
if (isset($_POST['gpbtn'])) {
	$gp_id=$_POST['doctor'];
	$_SESSION['gp_id']=$gp_id;

	$date = $_POST['appointment_date'];;
	$_SESSION['appointment_date']=$date;
	$dbdate= date('Y-m-d', strtotime($date));
	$day = date('D', strtotime($date)); 
	

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

	$sqlCheck_day_off= "SELECT day_off FROM gps WHERE gp_id=?";
	$check_day_off=$db->prepare($sqlCheck_day_off);
	$check_day_off->bindParam(1,$gp_id);
	$check_day_off->execute();
	$day_off= $check_day_off->fetchColumn();		
	

		if ($day != $day_off) {

				$sqlCheck_av= "SELECT COUNT(appointment_time) FROM appointments WHERE gp_id= ? AND appointment_date= ?";
				$check_av=$db->prepare($sqlCheck_av);
				$check_av->bindParam(1,$gp_id);
				$check_av->bindParam(2,$dbdate);
				$check_av->execute();
				$row_count = $check_av->fetchColumn();
				$_SESSION['appnumber']=$row_count;

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
	$check_app->bindParam(1,$_SESSION['pat_id']);
	$check_app->execute();
	$row_count = $check_app->fetchColumn();
	

	if ($row_count < 5) {

		if ((isset($_SESSION['gp_id'])) && isset($_SESSION['appointment_date']) && $_SESSION['appnumber']==0) {

			if (isset($_POST['appointment_time']) && isset($_POST['appointment_reason'])) {
				
			
			
				// if there are no problems, get the data from the front end
				$sqlInsert_app = "INSERT INTO appointments( gp_id, appointment_date, appointment_day, appointment_time, appointment_reason, user_id) VALUES
				(:gp_id,:appointment_date, :appointment_day, :appointment_time,:appointment_reason, :user_id)";
				//prepare the query
				
				$insert_app = $db->prepare($sqlInsert_app);
				// get the data from the front end and teh session
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
				'user_id' => $_SESSION['pat_id'],
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

			


//UPDATE AND DELETE
	

	//when the user type the appointment id number and press the confirm button, the connected gp id is found

	if (isset($_POST['confirmbtn'])) {

		$appointment_number= $_POST['appointment_number'];
		
		$sqlCheck_gp_id= "SELECT gp_id FROM appointments WHERE appointment_id= ?";
		$check_gp_id=$db->prepare($sqlCheck_gp_id);
		$check_gp_id->bindParam(1,$appointment_number);		
		$check_gp_id->execute();
		$gp_id= $check_gp_id->fetchColumn();

		if (!empty($gp_id)) {		
			$_SESSION['gp_id']=$gp_id;
			$_SESSION['appointment_number']= $appointment_number;
		}
		else{
			$error_wrongnumb = "Enter a valid appointment number";
			$_SESSION['Error_wrongnumb'] = $error_wrongnumb;
		}
	}

	// when the check button is pressed, check the gp availability in the specific date chosen by the user 

	if (isset($_POST['checkbtn'])) {

		if (isset($_SESSION['appointment_number'])){ 

			$date = $_POST['appointment_date'];;
			$_SESSION['appointment_date']=$date;
			$dbdate= date('Y-m-d', strtotime($date));
			$day = date('D', strtotime($date));
			
			if (isset($_SESSION['appointment_date'])) {

					


					$sqlCheck_day_off= "SELECT day_off FROM gps WHERE gp_id=?";
					$check_day_off=$db->prepare($sqlCheck_day_off);
					$check_day_off->bindParam(1,$_SESSION['gp_id']);
					$check_day_off->execute();
					$day_off= $check_day_off->fetchColumn();		

					switch ($_SESSION['gp_id']) {
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
				

					if ($day != $day_off) {

							$sqlTime_av= "SELECT appointment_time FROM appointments WHERE gp_id= ? AND appointment_date= ?";
							$time_av=$db->prepare($sqlTime_av);
							$time_av->bindParam(1,$_SESSION['gp_id']);
							$time_av->bindParam(2,$dbdate);
							$time_av->execute();
							$availability=$time_av->fetchColumn();
							$_SESSION['time']=$availability;
					}
					else{
						$error_day_off= "We are sorry but"." ".$gp_name." "."is not on duty on the date selected.";
						$_SESSION['Error_day_off']=$error_day_off;
					}
				}
				else{
					$error_nodate="Select a date from the form and click on the check button";
					$_SESSION['$Error_nodate']=$error_nodate;


				}
			}
			else{
				$error_noappnumb="Insert an appointment number in the form and click on the confirm button to proceed";
				$_SESSION['Error_noappnumb']= $error_noappnumb;
			}				
	}


    //set variables used in the select options to show gp availability
	$_8="8.00-9.00";
	$_9="9.00-10.00";
	$_10="10.00-11.00";
	$_11="11.00-12.00";
	$_13="13.00-14.00";
	$_14="14.00-15.00";
	$_15="15.00-16.00";
	$_16="16.00-17.00";
	$_17="17.00-18-00";



		if (isset($_POST['update'])) {

			if (isset($_SESSION['appointment_number']) && isset($_SESSION['appointment_date'])) {
			
				$appointment_number = $_SESSION['appointment_number'];
				$appointment_date = $_SESSION['appointment_date'];
				$day = date('D', strtotime($appointment_date));
				$appointment_time = $_POST['appointment_time'];
				$appointment_reason = $_POST['appointment_reason'];
				$sqlQuery = "UPDATE appointments SET appointment_date=?, appointment_day=?, appointment_time=?, appointment_reason=? WHERE appointment_id=?";
				$Query = $db->prepare($sqlQuery);
				
				$Query->bindParam(1,$appointment_date);
				$Query->bindParam(2,$day);
				$Query->bindParam(3,$appointment_time);
				$Query->bindParam(4,$appointment_reason);
				$Query->bindParam(5,$appointment_number, PDO::PARAM_INT);
				$Query->execute();


				$success_appup = "Appointment successfully updated";
				$_SESSION['Successup'] = $success_appup;

				$sec="3";
				header("Refresh:$sec");

		}
		else{
				$error_noupdate= "Insert an appointment number and select a date to update an appointment.";
				$_SESSION['Error_noupdate'] = $error_noupdate;
		}
	}


		if (isset($_POST['cancel'])) {

			if (isset($_SESSION['appointment_number'])) {

			$appointment_number = $_SESSION['appointment_number'];
			$query = $db-> prepare ('DELETE FROM appointments WHERE appointment_id = ?');
			$query->bindParam(1,$appointment_number, PDO::PARAM_INT);
			$query->execute();	

			$success_appdel = "Appointment successfully deleted";
				$_SESSION['Successdel'] = $success_appdel;

				$sec="3";
				header("Refresh:$sec");
		}

		else{
				$error_nocancel= "Insert an appointment number  to cancel an appointment.";
				$_SESSION['Error_nocancel'] = $error_nocancel;
		}
	}


?>

<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>Appointments - Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="reception_appointments-stylesheet.css">
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
				<li><a href="reception_appointments.php">Appointment</a></li>  
				<li><a href="https://dashboard.tawk.to/#/dashboard"  target="_blank">Chat</a></li>    
			   <li><a href="../patients/GP_log_out.php" id="log_out"> Log out</a></li>
			    <li><div id="hello_user"><?php  echo 'Hi'.' ' .$_SESSION['username'];?></div></li>
			    </ul>
			  </div>
			</nav> <!--navbar end -->
	




	<div class="flex-container" id="central"> <!-- central  container start-->

		<div class="container-fluid" id="articlecont"> <!-- article start-->
			<h1 id="h1cent">
				<b>My Appointments </b> 
			</h1>
		</div>     <!-- article end-->

		<div class="container-fluid" id="contcent">


			<div id="conttable">
				<table id="table">
					<tr id="tr1">
						<th id="th1">Appointment number</th>
						<th id="th2">Date</th>
						<th id="th3">Day</th>
						<th id="th4">Time</th>
						<th id="th5">GP</th>
						<th id="th6">Reason</th>
					</tr>
						<?php  

						if (isset($_SESSION['patient_username'])) {
							

							$patient_id = $_SESSION['pat_id'];

							// Fetch appointment details and dislpay those in a table					
							$sqlQuery = 'SELECT appointment_id, gp_id, appointment_date, appointment_day, appointment_time, appointment_reason FROM appointments WHERE user_id = ?';
							$query = $db-> prepare ($sqlQuery);
							$query->bindParam(1,$patient_id);
							$query->execute();
								
							while ($row = $query->fetch(PDO::FETCH_ASSOC)){
								$date = date("d-m-Y",strtotime($row['appointment_date']));
								$Gp_id= $row['gp_id'];
								switch ($Gp_id) {
									case 1:
										$gp_name= "Dr. Kelso";
										break;
									
									case 2:
										$gp_name= "Dr. Cox";
										break;

									case 3:
										$gp_name= "Dr. Dorian";
										break;

									case 4:
										$gp_name= "Dr. Reid";
										break;

									case 5:
										$gp_name= "Nurse Espinosa";
										break;

									case 6:
										$gp_name= "Nurse Roberts";
										break;

								}

								echo "<tr>";
								echo "<td>".$row['appointment_id']."</td>";
								echo "<td>".$date."</td>";
								echo "<td>".$row['appointment_day']."</td>";
								echo "<td>".$row['appointment_time']."</td>";
								echo "<td>".$gp_name."</td>";
								echo "<td>".$row['appointment_reason']."</td>";
								echo "</tr>";}

							}
						?>

				</table>
			</div>



			<!--Check patient id -->
			<div>
				<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" id="pat_uname_form">
							<div class="form-group">
								<label class="control-label" for="patient_username">Patient username:</label>
								 	<input class="form-control" type="text" name="patient_username" placeholder="Insert patient username" id="patuname">

							</div>	 				
								<button type="submit" class="btn btn-success" name="pat_uname_btn" value="pat_uname_btn">
									Check patient username
								</button> 
							
				</form>
			</div>

			<!--BOOKING -->



			<div>


			<div class="col-md-6" id="bookingcont">

			<p>Choose a doctor or a nurse and pick a date, then click on the choose button</p>

			<form class="form" id="gpform" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" >
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


			<form class="form" id="gpform" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" > 

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

				
        	<!--UPDATE DELETE FORM -->
			<div>

				<div class="col-md-6" id="formcont">

						<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" id="appnumbform">
							<div class="form-group">
								<label class="control-label" for="appid">Appointment number:</label>
								 	<input class="form-control" type="text" name="appointment_number" placeholder="Insert number" id="appid" value="<?php if(isset($_SESSION['appointment_number'])){echo $_SESSION['appointment_number'];} else{echo "";} ?>">

							</div>	 				
								<button type="submit" class="btn btn-success" name="confirmbtn" value="confirm">
									Confirm
								</button> 
							
						</form>


						<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" id="appdateform" >
							<div class="form-group">
								<label class="control-label" for="appdate">Date:</label>
								 	<input class="form-control" type="date" name="appointment_date"  id="appdate" value="<?php if(isset($_SESSION['appointment_date'])){echo $_SESSION['appointment_date'];} else{echo "Select";} ?>">

							</div>	 				
								<button type="submit" class="btn btn-success" name="checkbtn" value="check">
									Check
								</button> 
							
						</form>



						<form class="form-inline" action="<?php echo $_SERVER["PHP_SELF"];?>" method="POST" id="appform">
							 
							<div class="form-group">
							 <label class="control-label" for="appid">Time:</label>
							 <select class="form-control" name="appointment_time" id="apptime">				<!-- time picker -->
									<option style="color:<?php if (isset($availability)){echo ($availability == $_8 ? 'red' : '#21c60d');} else {echo "black";} ?>">8.00-9.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_9 ? 'red' : '#21c60d');} else {echo "black";} ?>">9.00-10.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_10 ? 'red' : '#21c60d');} else {echo "black";} ?>">10.00-11.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_11 ? 'red' : '#21c60d');} else {echo "black";} ?>" >11.00-12.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_13 ? 'red' : '#21c60d');} else {echo "black";} ?>" >13.00-14.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_14 ? 'red' : '#21c60d');} else {echo "black";} ?>" >14.00-15.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_15 ? 'red' : '#21c60d');} else {echo "black";} ?>" >15.00-16.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_16 ? 'red' : '#21c60d');} else {echo "black";} ?>" >16.00-17.00</option>
									<option style="color:<?php if (isset($availability)){echo ($availability == $_17 ? 'red' : '#21c60d');} else {echo "black";} ?>" >17.00-18.00</option>
								</select>
								</div>					  
							  <br><br>
							  <div class="form-group">
							  <label class="control-label" for="appreason">Reason:</label>
							  <select class="form-control" name="appointment_reason" id="appreason">   <!-- reason picker -->
								  <option>All routine GP appointments</option>
								  <option>Flu immunisation clinics</option>
								  <option>blood tests</option>
							  </select>
							  </div>
							  <br>

							  <div id="contbutton">					
								  <input type="submit" class="btn btn-success" name="update" value="Update">
								  <input type="submit" class="btn btn-danger" name="cancel" value="Cancel">
							 </div>

						</form>
					</div>

				<?php


					if (isset($_SESSION['Successup'])) {
						echo '<div id="success_appup">'.$_SESSION['Successup'].'</div>';
						unset($_SESSION['Successup']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Successdel'])) {
						echo '<div id="success_appdel">'.$_SESSION['Successdel'].'</div>';
						unset($_SESSION['Successdel']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Error_noupdate'])) {
						echo '<div id="error_noupdate">'.$_SESSION['Error_noupdate'].'</div>';
	        			unset($_SESSION['Error_noupdate']);
	        			unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Error_nocancel'])) {
						echo '<div id="error_nocancel">'.$_SESSION['Error_nocancel'].'</div>';
	        			unset($_SESSION['Error_nocancel']);
	        			unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}


					if (isset($_SESSION['Error_wrongnumb'])) {
						echo '<div id="error_wrongnumb">'.$_SESSION['Error_wrongnumb'].'</div>';
						unset($_SESSION['Error_wrongnumb']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Error_day_off'])) {
						echo '<div id="error_day_off">'.$_SESSION['Error_day_off'].'</div>';
						unset($_SESSION['Error_day_off']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Error_nodate'])) {
						echo '<div id="error_nodate">'.$_SESSION['Error_nodate'].'</div>';
						unset($_SESSION['Error_nodate']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Error_noappnumb'])) {
						echo '<div id="error_noappnumb">'.$_SESSION['Error_noappnumb'].'</div>';
						unset($_SESSION['Error_noappnumb']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}

					if (isset($_SESSION['Error_wrongpatid'])) {
						echo '<div id="error_wrongpatid">'.$_SESSION['Error_wrongpatid'].'</div>';
						unset($_SESSION['Error_wrongpatid']);
						unset($_SESSION['gp_id']);
						unset($_SESSION['gp_name']);
						unset($_SESSION['appointment_date']);
						unset($_SESSION['appointment_number']);
						unset($_SESSION['gp_time']);
					}

				?>	


			</div>
				
			



		</div>
	</div>


</div>

</body>
</html>