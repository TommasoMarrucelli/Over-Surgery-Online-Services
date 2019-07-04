<?php
    //start the session
    session_start();

    
        // See if a login form was submitted with a username for login
        if (isset($_POST['loginbtn'])) {
            // get the username
            $username =htmlentities(trim( $_POST['username']));
            // get the password
            $password = htmlentities(trim($_POST['password']));
            
            
            //connect to database
            include '../includes/pdo_connect.php';
            
            
            // Look up the user-provided credentials
            $query = 'SELECT username FROM users WHERE username = ?';
            $result = $db->prepare($query);
            $result->bindParam(1, $username);
            $result->execute();
            $uname= $result->fetchColumn();

            if (!empty($uname)) {
                $sqlCheckpwd = 'SELECT password FROM users WHERE username = ?';
                $checkpwd = $db->prepare($sqlCheckpwd);
                $checkpwd->bindParam(1, $uname);
                $checkpwd->execute();
                $pwd= $checkpwd->fetchColumn();

                if(password_verify($password, $pwd)) {

                  
                  //set the session to store the username
                  $_SESSION['username'] = $uname;
                  
                  //Check the id to store it and the role of user to send him to the right page 
                                      
                  $sqlCheckdet = 'SELECT role, user_id FROM users WHERE username=?';
                  $checkdet=$db->prepare($sqlCheckdet);
                  $checkdet->bindParam(1,$_SESSION['username']);
                  $checkdet->execute();
                  $details=$checkdet->fetch(PDO::FETCH_ASSOC);
                  $user_id=$details['user_id'];
                  $_SESSION['user_id']=$user_id;
                  $role=$details['role'];

                  if ($role == 'pat') {
                    header("Location:http://localhost/GP OVER SURGERY/patients/GP_dashboard.php");
                  }else{
                    header("Location:http://localhost/GP OVER SURGERY/reception/reception_appointments.php");
                  }           
                          
              }
              else{
                $errorwrongdet="Username and/or password are wrong. Please insert valid username and password";
                $_SESSION['Errorwrongdet'] = $errorwrongdet;
                $sec=2;
                header("refresh:$sec");            
              }

            }
            else{
              $errorwrongdet="Username and/or password are wrong. Please insert valid username and password";
              $_SESSION['Errorwrongdet'] = $errorwrongdet;
              $sec=2;
              header("refresh:$sec");
            }

            
            
            
                
                

            }
            
          
       

?>

<!DOCTYPE html>
<html>
<head>
  <title>Log In- Over Surgery Online Services</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">  <!-- to adapt layout to mobile-->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <link rel="stylesheet" type="text/css" href="GP_log_in-stylesheet.css">
</head>
<body>
  <div class="flex-container" id="header">     <!-- header start -->

    <div style="flex-grow: 0" id="contimg">
        <img src="../Images/nhs.jpg" id="img">  <!--image-->
    </div>

    <div style="flex-grow: 10" id="conthead">
      <h1 id="h1head">Over Surgery Online Services </h1> <!-- page title-->
    </div>                    <!-- header end -->

  
  



  </div>                          <!-- central container start -->
  <div class="flex-container" id="central">



    <div class="container-fluid" id="contcent">         <!-- article start-->
      <h1 id="h1cent">
        <b>Once you log in, you can use this website to: </b> <br>
          <ul id="ul">
            <li>Check the GPs or nurses’s availability;</li>
            <li>To book, change and cancel an appointment;</li>
            <li>To extend the prescriptions;</li>
            <li>To check the results of tests you have done;</li>
            <li>To chat online with our receptionists;</li>
          </ul> 
      </h1>
      
    </div>                      <!--article end -->         




    <div class="panel panel-default" id="panelcent">      <!--form start -->
      <div class="panel-body" id="panelcentbody"> 

        <form action="GP_log_in.php" method="POST" id="formlogin">  <!-- form inputs start-->
          <div class="form-group">
            <label for="username">Username</label>
            <div class="input-group">
              <span class="input-group-addon" id="icons"> 
                <i class="glyphicon glyphicon-user"></i>
              </span>
              <input type="text" class="form-control"  placeholder="Enter Username" id="username" name="username">
            </div>
          </div>
          <div class="form-group">

            <label for="pwd">Password:</label>
            <div class="input-group">
              <span class="input-group-addon" id="icons">
                <i class="glyphicon glyphicon-lock"></i>
              </span>
              <input type="password" class="form-control" placeholder="Enter password" id="pwd" name="password">
            </div>
          </div>                        <!-- form inputs end -->
          <br>


          <button type="submit" class="btn btn-success" name="loginbtn" value="loginbtn" id="loginbtn">   <!-- log in button -->
            Log in 
          </button>
          <br><br>


          <!--display errors-->
          <?php

          if (isset($_SESSION['Errorwrongdet'])) {
                echo '<div id="errorwrongdet">'.$_SESSION['Errorwrongdet'].'</div>';
                unset($_SESSION['Errorwrongdet']);
               
          }
          ?>

          


          <span id="registerlink">                     <!-- resgistration form link-->
              <u><a href="GP_registration.php" style="color: white" target="_top"> Register here</a></u>           
          </span>
        </form>

      </div>
    </div>    
  </div>                                      <!--form end -->

</body>
</html>