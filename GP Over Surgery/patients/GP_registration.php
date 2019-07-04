<?php


//if registration button is pressed 
if (isset($_POST['register'])){


//data are checked; if there are problems, an error message is displayed
$title = $_POST['title'];
$name = htmlentities(trim( $_POST['name']));
$surname =htmlentities(trim( $_POST['surname']));
$email = htmlentities(trim( $_POST['email']));
$phone = htmlentities(trim( $_POST['phone']));
$username = htmlentities(trim( $_POST['username']));
$password = htmlentities(trim( $_POST['password']));
//password and confirm password must have the same value
	if($_POST['password'] != $_POST['confpassword']){
	    echo "Your passwords did not match";
	    die();
	 }

	 if (!$title || !$name || !$email || !$phone || !$username || !$password || !$_POST['confpassword']) {

		$errormissdet = 'Please insert all the details requested.';
		$_SESSION['Errormissdet']= $errormissdet;
		}
	else{
		//connect to database
		include '../includes/pdo_connect.php';
		// encrypt the password
		$encrypt_password = password_hash($password, PASSWORD_DEFAULT);
		// the users that register from here will be patients for sure
		$role='pat';

		// if ther are no problems, get the data from the front end
		$sqlQuery = "INSERT INTO users(role,title, name, surname, email, phone, username, password) VALUES
		(:role, :title, :name, :surname, :email, :phone, :username, :password)";
		//prepare the query
		$query = $db->prepare($sqlQuery);

		//execute the query
		$query->execute(array(
		':role' => $role,
		':title' => $title,
		':name' => $name,
		':surname' => $surname,
		':email' => $email,
		':phone' => $phone,
		':username' => $username,
		':password' => $encrypt_password
		));
			
		header("location: http://localhost/GP OVER SURGERY/patients/GP_log_in.php");
		exit;

	 }
	}
?>

<!DOCTYPE HTML>
<!DOCTYPE html>
<html>
<head>
	<title>Registration Form</title>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="GP_registration-stylesheet.css">
  
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



	<div class="flex-container" id="central">    <!--central container start -->

		

		<div class="panel panel-default" id="panelcent"> <!-- registration form start-->
			<div class="panel-body" id="panelcentbody">	
				<h1 id="h1panel">Register Form </h1>

				<form class="form-horizontal" action="GP_registration.php"  method="POST" id="formlogin"> <!-- form inputs start-->


				 <?php
				 	// display errors
			          if (isset($_SESSION['Errormissdet'])) {
			                echo '<div id="errormissdet">'.$_SESSION['Errormissdet'].'</div>';
			                unset($_SESSION['Errormissdet']);
			               
			          }
			       ?>

				  <div class="form-group" >
				    <label for="name" class="col-sm-2 control-label">Title:</label>
				    <div class="col-sm-10">
				    	<label class="radio-inline">
					  		<input type="radio" name="title" value="Mr." required> Mr.<br>
						</label>
						<label class="radio-inline">
					 		 <input type="radio" name="title" value="Ms."> Ms.<br>
						</label>
				  </div>	



				  <div class="form-group" id="formgroups">
				    <label for="name" class="col-sm-2 control-label">Name:</label>
				    <div class="col-sm-10">
					    <div class="input-group">
					    	<span class="input-group-addon" id="icons"> 
			    				<i class="glyphicon glyphicon-user"></i>
			    			</span>
					    	<input type="text" class="form-control" id="name" name="name">
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
				    		<input type="text" class="form-control" id="surname" name="surname">
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
				    		<input type="email" class="form-control" id="email" name="email">
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
				    		<input type="text" class="form-control" id="phone" name="phone">
				    	</div>
				    </div>
				  </div>


				   <div class="form-group" id="formgroups">
				    <label for="address" class="col-sm-2 control-label">Username:</label>
				    <div class="col-sm-10">	
				    	<div class="input-group">
				    		<span class="input-group-addon" id="icons">
				    			<i class="glyphicon glyphicon-user"></i>
				    		</span>
				    		<input type="text" class="form-control" id="username" name="username">
				    	</div>
				    </div>
				  </div>


				  <div class="form-group" id="formgroups">
				    <label for="password" class="col-sm-2 control-label">Password:</label>
				    <div class="col-sm-10">	
				    	<div class="input-group">
				    		<span class="input-group-addon" id="icons">
				    			<i class="glyphicon glyphicon-lock"></i>
				    		</span>
				    		<input type="password" class="form-control" id="password" name="password">
				    	</div>
				    </div>
				  </div>


				  <div class="form-group" id="formgroups">
				    <label for="confpassword" class="col-sm-2 control-label">Confirm password:</label>
				    <div class="col-sm-10">	
				    	<div class="input-group">
				    		<span class="input-group-addon" id="icons">
				    			<i class="glyphicon glyphicon-lock"></i>
				    		</span>
				    		<input type="password" class="form-control" id="confpassword" name="confpassword">
				    	</div>
				    </div>
				  </div>			<!--form inputs end -->




				  <br><br>
				  <button type="submit" class="btn btn-success" name="register" value="register" id="registerbtn">Register</button>  <!-- registration button-->
				  <br><br>

				  <span id="loginlink">   <!-- log in link -->
		  			  If you are already registered, you can <br>
		  			  <u><a href="GP_log_in.php" style="color: white" target="_top"> Log in</a></u>
				  </span>
				

				</form>

			</div>
		</div>		
	</div>

</body>
</html>








