<?php 
  session_start();
 //connect to database 
  include '../includes/pdo_connect.php';

  $user_id = $_SESSION['user_id'];

  // display current profile details in the form
  $sqlSelect = 'SELECT title, name, surname, email, phone, username, password FROM users WHERE user_id = ?';
            $query = $db-> prepare ($sqlSelect);
            $query->bindParam(1,$user_id);
            $query->execute();             
            $row = $query->fetch(PDO::FETCH_ASSOC);

  if (isset($_POST['applychanges'])) {

    $title =  $row['title'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $row['username'];
    $password = $row['password'];
  
    $sqlUpdate = "UPDATE users SET title=?, name=?, surname=?, email=?, phone=?, username=?, password=? WHERE user_id=?";
    $Query = $db->prepare($sqlUpdate);
    
    $Query->bindParam(1,$title);
    $Query->bindParam(2,$name);
    $Query->bindParam(3,$surname);
    $Query->bindParam(4,$email);
    $Query->bindParam(5,$phone);
    $Query->bindParam(6,$username);
    $Query->bindParam(7,$password);
    $Query->bindParam(8,$user_id, PDO::PARAM_INT);
    $Query->execute();


    $sec=0;
    header("refresh:$sec");

}

?>


<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>My Profile - Over Surgery Online Services</title>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="GP_my_profile-stylesheet.css">
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


		<div class="panel panel-default" id="panelcent">   <!--central panel start -->
			<div class="panel-body" id="panelcentbody">	
                	<h1 id="h1panel">My Profile </h1>
                <form class="form-horizontal" action=""  method="POST" id="formlogin"> <!-- form inputs start-->
                    <div class="form-group" id="formgroups">
                      <label for="name" class="col-sm-2 control-label">Name</label>
                      <div class="col-sm-10">
                          <div class="input-group">
                              <span class="input-group-addon" id="icons"> 
                                  <i class="glyphicon glyphicon-user"></i>
                              </span>
                              <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name'];?>">
                          </div>
                       </div> 
                    </div>
  
  
                    <div class="form-group" id="formgroups">
                      <label for="surname" class="col-sm-2 control-label">Surname:</label>
                      <div class="col-sm-10">	
                          <div class="input-group">
                              <span class="input-group-addon" id="icons">
                                  <i class="glyphicon glyphicon-user"></i>
                              </span>
                              <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $row['surname'];?>">
                          </div>
                      </div>
                    </div>
  
  
                    <div class="form-group" id="formgroups">
                      <label for="email" class="col-sm-2 control-label">E-mail:</label>
                      <div class="col-sm-10">	
                          <div class="input-group">
                              <span class="input-group-addon" id="icons">
                                  <i class="glyphicon glyphicon-envelope"></i>
                              </span>
                              <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email'];?>">
                          </div>
                      </div>
                    </div>
  
  
                    <div class="form-group" id="formgroups">
                      <label for="phone" class="col-sm-2 control-label">Phone number:</label>
                      <div class="col-sm-10">	
                          <div class="input-group">
                              <span class="input-group-addon" id="icons">
                                  <i class="glyphicon glyphicon-earphone"></i>
                              </span>
                              <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $row['phone'];?>">
                          </div>
                      </div>
                    </div>
  
  
                     <!--form inputs end -->
  
  
  
  
                    <br><br>
                    <button type="submit" class="btn btn-success" name="applychanges" value="applychanges" id="updatebtn">Apply Changes</button>  <!-- registration button-->
                    <br><br>
                  
                  </form>

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