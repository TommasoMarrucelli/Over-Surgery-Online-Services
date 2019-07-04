<?php
    //start the session
    session_start();
    
    session_unset();
    
    header("location: http://localhost/GP OVER SURGERY/patients/GP_log_in.php");
    exit();
    
?>