<?php
session_start()?>

<!-----Μήνυμα αποσύνδεσης---->
<script> alert("Thank you!"); </script> 

<!DOCTYPE html>
<html>
    <style>
          button{
                position: relative;
                margin: 250px 40px 20px 600px;
              padding:6px;
            }
    </style>
    <body style="background-color: #ffcccc">
    
<?php

    $con = mysqli_connect("localhost","root","");
    if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
    die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
    mysqli_select_db($con,"test1"); //Επιλογή βάσης test
   

   //-----remove all session variables-------//
      session_unset(); 
//------destroy the session-------//
session_destroy(); 
sleep(2);

?>
   <?php if (1) { ?><!------Μήνυμα επιτυχημένης αποσύνδεσης----->
    <script>
       alert("You are logged out!");
 //-----Σε περίπτωση αποσύνδεσης ο χρήστης μεταβαίνει στην αρχική σελίδα(Welcome.php)--------//
   </script> <?php } ?>
    <button onclick="window.location.href='welcome.php'">Back to Start Page</button>
    
    
    
</body>
</html>