<?php
$dsn = 'mysql:host=localhost;dbname=over_surgery';
$user = 'root';
$dbpassword = '';

try {
            $db = new PDO($dsn, $user, $dbpassword);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //           echo 'Connection failed: ' . $e->getMessage();
            die('Sorry, database problem');
        }
?>




        