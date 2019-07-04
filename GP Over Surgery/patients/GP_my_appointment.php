<?php 
	session_start();

	//connect to database
	include '../includes/pdo_connect.php';



	
	//when the user type the appointment id number and press the confirm button, the corrisponding gp id is found

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


 //update appointments
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

// cancel appointments
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
	<title>My Appointments - Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_my_appointment-stylesheet.css">
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

							$user_id = $_SESSION['user_id'];
							// Fetch appointment details and dislpay those in a table					
							$sqlQuery = 'SELECT appointment_id, gp_id, appointment_date, appointment_day, appointment_time, appointment_reason FROM appointments WHERE user_id = ?';
							$query = $db-> prepare ($sqlQuery);
							$query->bindParam(1,$user_id);
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
						?>

				</table>
			</div>

			<div id="contform">
				<div class="col-md-6">

				<h1 id="h1form">																				
					Here you can cancel or modify your appointments:<br><br>
					<ul>
						<li>To change the details of your appointment, insert the appointemnt number and the new details in the form and click the update button.</li>
						<li>Insert the appointment number in the form and click the cancel button to cancel your appointment.</li><br>
				</ul>
				</h1>
			</div>

			<div class="col-md-6">

				<div id="formcont">


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

				?>	


			</div>
				
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





<?php


require(__DIR__.'\vendor\solarium\solarium\examples/init.php');
htmlHeader();

// create a client instance
$client = new Solarium\Client($config);

    $title = "";
    $content = "";
    $url = "";
    $OR1 = "";
    $OR2 = "";
    $AND =" AND ";
    $par1 = ")";
    $par2 = ")";
    $par3 = ")";
    $search = array();  
     




    if( isset($_POST['btn'])){

         $i = $_POST['count'];

        if($i != 0){

            $keywords =htmlentities(trim( $_POST['keywords0']));
                
                if (isset($_POST['url'])) {
                    $url = '(url:"'.$keywords.' ")^1.25'.$par3;
                    $OR1 = ' OR ';
                    $OR2 = ' OR ';
                    $par1 = "";
                    $par2 = "";

                }

                if (isset($_POST['content'])) {
                    $content = '(content:"'.$keywords.' ")'.$OR2.$par2;
                    $OR1 = ' OR ';
                    $par1 = "";
                }

                if (isset($_POST['title'])) {
                    $title = '((title:"'.$keywords.'") ^1.5'.$OR1.$par1;
                }

                $searchTerm = $title.$content.$url.$AND;
                $search[] = $searchTerm;

            while ( $i >= 1) {

                $OR1 = "";
                $OR2 = ""; 
                $titleN = "";
                $contentN = "";
                $urlN = "";  


              $keywordsN =htmlentities(trim( $_POST['keywords'.$i]));

                if (isset($_POST['url'.$i])) {
                    $urlN = '(url:"'.$keywordsN.' ")^1.25'.$par3;
                    $OR1 = ' OR ';
                    $OR2 = ' OR ';
                    $par1 = "";
                    $par2 = "";
              
                }

                if (isset($_POST['content'.$i])) {
                    $contentN = '(content:"'.$keywordsN.' ")'.$OR2.$par2;
                    $OR1 = ' OR ';
                    $par1 = "";
                }

                if (isset($_POST['title'.$i])) {
                    $titleN = '((title:"'.$keywordsN.'") ^1.5'.$OR1.$par1;
                }
                 
                 

                $searchTermN = $titleN.$contentN.$urlN;
                $search[] = $searchTermN;
                $search[] = $AND;
                $i--;
            }

            //remove the last "AND"
            array_pop($search);

?>

<script type="text/javascript"> 
//reset the count number of keywords
 
$(document).ready(function resetCount(){
    resetCount = 0;
     document.getElementById('count').value = resetCount;});
</script>


<?php


                
            }
            
        

        else {
            $keywords =htmlentities(trim( $_POST['keywords0']));

                if (isset($_POST['url'])) {
                    $url = '(url:"'.$keywords.' ")^1.25';
                    $OR1 = ' OR ';
                    $OR2 = ' OR ';
                }

                if (isset($_POST['content'])) {
                    $content = '(content:"'.$keywords.' ")'.$OR2;
                    $OR1 = ' OR ';
                }

                if (isset($_POST['title'])) {
                    $title = '(title:"'.$keywords.'") ^1.5'.$OR1;
                }
                 
                 

                $searchTerm = $title.$content.$url;
                $search[] = $searchTerm;


         }


        // get a select query instance
$query = $client->createSelect();
// apply settings using the API
$keySearch = implode($search);
$query->setQuery($keySearch);
$query->setStart(0)->setRows(20);
$query->setFields(array('url','title','content'));


// this executes the query and returns the result
$resultset = $client->select($query);
$numFound = $resultset->getNumFound();

}



?>    

<!DOCTYPE HTML>  
<html>

<head>
<meta charset="utf-8">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 <link rel="stylesheet" type="text/css" href="home.css">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <title>SEARCH ENGINE</title>
</head>


<body>  
    <div id="titlediv">
        <h1 id="title"> Title</h1>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-10" id="panelcent">    
        <form action="home.php" method="POST" id="form">  
          <div class="table-responsive">
               <table id="dynamic">  
                    <tr id="row">
                        <td id="search_td">
                             <div id="keydiv">
                               <label for="keywords">Keyword</label>
                                <div class="input-group" >
                                  <input type="text" class="form-control" id="keywords" name="keywords0">
                                </div>
                            </div> 
                        </td>

                        <td id="search_td">
                            <div id="searchLocation">
                            <span>
                            <label>Search the keyword in:</label>
                            </span>
                            <span>
                            <label class="radio-inline">
                                <input type="checkbox" name="title" value="title" checked="checked"> title <br>
                            </label>
                            <label class="radio-inline">
                                 <input type="checkbox" name="content" value="content" checked="checked"> content<br>
                            </label>
                            <label class="radio-inline">
                                 <input type="checkbox" name="url" value="url" checked="checked"> url<br>
                            </label>
                            </span>
                            </div>
                        </td> 
                    </tr>
                </table>
           </div>
           <div>          
                    <button type="button" name="add" id="add" value = "add" class="btn btn-success">Add keyword
                    </button>
            </div>

            <input type="hidden" name="count" id="count" value="0" />

            <br><br>

          <button type="submit" class="btn btn-info" id="btn" name="btn" value="btn">   
           search 
          </button>
          <br><br>

      </form>

      <div>
          <?php
          if (isset($_POST['btn'])) {
              

            // display the total number of documents found by solr
            echo($keySearch."<br>");
            echo $numFound.' results';

            // show documents using the resultset iterator
            foreach ($resultset as $document) {


                echo '<hr/><table>';
                echo '<tr><th>title</th><td>' . $document->title . '</td></tr>';
                echo '<tr><th>url</th><td> <a href='.$document->url.' target="_blank">'.$document->url.'</a> </td></tr>';
                
                echo '</table>';
            }          }
          ?>
      </div>
  </div>
       
</body>
</html>


<script>
    //add a new search box for another keyword
 $(document).ready(function(){  
      var i=0;  
      $('#add').click(function(){  
           i++;  
           $('#dynamic').append('<tr id="row"> <td id="search_td"><div id="keydiv"><label for="keywords">Keyword</label><div class="input-group" ><input type="text" class="form-control" id="keywords" name="keywords'+i+'"></div></div> </td><td id="search_td"><div id="searchLocation"><span><label>Search the keyword in:</label></span><span><label class="radio-inline"><input type="checkbox" name="title'+i+'" value="title" checked="checked"> title <br></label><label class="radio-inline"><input type="checkbox" name="content'+i+'" value="content" checked="checked"> content<br></label><label class="radio-inline"><input type="checkbox" name="url'+i+'" value="url" checked="checked"> url<br></label></span></div></td> </tr>'); 

           document.getElementById('count').value = i; 
      });    
      
 });  

 </script>









