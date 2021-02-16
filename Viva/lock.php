<?php
session_start();
?>

<html>
    <title>Lock Page</title>
    <style>
        body{
            background-color: antiquewhite;
        }
        h3{
            position:relative;
            border: black dashed 3px;
            background-color: aliceblue; 
            border-radius:5px;
            width:35%;
            padding:5px;
            text-align: center;
            margin-top:75px;
            margin-left:420px;
        }
         button{
             margin-top:50px;
             margin-left:580px;
             font-size:14px;
            font-family: sans-serif;
            background-color: #ffffff;
            border-radius:5px;
            width:12%;
            }
    </style>
    <body>
          <?php 
      $con = mysqli_connect("localhost","root","","test1");
         if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
    die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
     mysqli_set_charset($con,'utf8');//set charset=utf8
 //Στο παρακάτω ερώτημα ενημερώνουμε τον πίνακα login σε περίπτωση που έχουν περάσει
 //είκοσι(20) λεπτά από την τελευταία δραστηριότητα του πελάτη.Έπειτα μεταβαίνει
// αυτόματα στην αρχική σελίδα για νέα προσπάθεια σύνδεσης.
  $lock_name=$_SESSION["username_l"];//Αναζήτηση στον πίνακα και "εξαγωγή" ονόματος κλειδωμένου χρήστη.
  
  $sqlu="UPDATE login SET failed_logins=0,status='UNLOCKED' WHERE username='$lock_name'"
   . " AND last_activity < DATE_SUB(NOW(),INTERVAL 2 MINUTE)";
  
   $res_u= mysqli_query($con, $sqlu);//Εκτέλεση ερωτημάτων
   $count_l= mysqli_affected_rows($con);
   
if ($count_l>0) 
        {//Αν υπάρχουν γραμμές που επηρεάστηκαν από το ερώτημα τότε
    //πηγαίνουμε στην αρχική σελίδα.
        $_SESSION["a"]=1;
         header("Location:http://localhost/Viva/welcome.php");
        }//end-if
        
?>
        <h3>Your account is Locked.Please try again Later</h3>
       </body>
</html>


