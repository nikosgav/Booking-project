<?php
session_start();

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    
    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <style>
            #b{
             font-size:14px;
            font-family: sans-serif;
            background-color: #ffffff;
            border-radius:5px;
            width:8%;
            }
            p{
                color: red;
            }
            body{
                background-color: cadetblue;
            }
            h3{
           padding:5px;
           font-family: inherit;
           border: cornflowerblue dotted 4px;
           width:30%;
           background-color: ivory;
            }
        </style>
    </head>
    <body>
       
      <?php 
      $con = mysqli_connect("localhost","root","","test1");
         if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
    die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
    
    mysqli_set_charset($con,'utf8');//set charset=utf8
    
    if ($_SERVER["REQUEST_METHOD"]=="POST") {
    //Αν τα δεδομένα στάλθηκαν με την μέθοδο POST τότε έχουμε τα παρακάτω:  
      $myusername = mysqli_real_escape_string($con,$_POST['username']);
      $mypassword = mysqli_real_escape_string($con,$_POST['pass']); 
        
   $sql="SELECT * FROM login WHERE username= '$myusername' AND pass= '$mypassword'";
   $res= mysqli_query($con, $sql);//Εκτέλεση ερωτημάτων
   $row= mysqli_fetch_array($res,MYSQLI_ASSOC);
 
//Επιστροφή αποτελέσματος ως πίνακα
   $count= mysqli_num_rows($res);
  
//Αναζήτηση στον πίνακα πελατών με όνομα χρήστη 
//και κωδικό τα πεδία που εισήγαγε ο χρήστης
//Αν τα στοιχεία που εισήγαγε ο χρήστης είναι σωστά τότε ο χρήστης πηγαίνει στην 
//σελίδα "Ο λογαρισμός μου".Αν ο κωδικός είναι λάθος τότε κλειδώνεται ο λογαριασμός
//και τέλος αν δεν υπάρχει ούτε το όνομα χρήστη ούτε ο κωδικός τότε μεταβαίνει ο 
//χρήστης στην σελίδα εγγραφής.
   
   if($count>0){
//---Ορισμός μεταβλητών συνόδου----//
       $_SESSION["username"]=$row["username"];
       $_SESSION["id"]=$row["id"];
       $_SESSION["x"]=1;
        header("Location:http://localhost/Viva/myprofile.php");
        
       }//end-3rd-if
       
      else  if($count==0){ ?>
          <?php //Επιλογή ονόματος χρήστη από πίνακα login
    $sqls="SELECT * FROM login WHERE username= '$myusername'";
   $ress= mysqli_query($con, $sqls);
   $row_l= mysqli_fetch_assoc($ress);
  $counts= mysqli_num_rows($ress); 
    
     if($counts>0){
      ?><script>alert('Invalid username or password!'); </script> 
          <?php
 //Αν δοθεί λάθος κωδικός τότε εκχωρούμε το πεδίο failed_logins που αναπαριστά
 //τον αριθμό που έδωσε ο χρήστης λάθος κωδικό,σε μια μετβλητή συνόδου
 //και αν ο χρήστης δώσει τρεις φορές λανθασμένο κωδικό τότε κλειδώνεται ο 
 //λογαριασμός του,αφού προηγουμένως έχει ενημερωθεί το πεδίο failed_logins.
          
          $fail="UPDATE login SET failed_logins=failed_logins+1 WHERE username='$myusername'";
         $resf= mysqli_query($con, $fail);//Ενημέρωση τιμής
             $fail_l="SELECT * FROM login WHERE username='$myusername'";
            $resf_l= mysqli_query($con, $fail_l);
          $row=mysqli_fetch_array($resf_l,MYSQLI_ASSOC);
          $_SESSION["username_l"]=$row['username'];
        $_SESSION["attempts"]=$row['failed_logins'];//Ορισμός μεταβλητή συνόδου ως 
        //τον αριθμό προσπαθειών σύνδεσης
        
         if($_SESSION["attempts"]==="3"){//Κλείδωμα λογαριασμού και ενημέρωση τιμής
      $sqllock="UPDATE login SET status='LOCKED' WHERE failed_logins='3'";
      $reslock=mysqli_query($con, $sqllock);
     ?>
      <!--Αν ο αριθμός προσπαθειών είναι τρεις τότε-->
      <script>alert("You have attempted 3 tries! Your Account is Locked");
          function lock()//Κάνουμε την ιδιότητα disable ενεργή.
          {document.getElementById("u").disabled=true;
           document.getElementById("p").disabled=true;
           document.getElementById("l").disabled=true; 
      }  
      function unlock(){//"Εμφάνιση κουμπιού για μεταφορά σε άλλη σελίδα
          //μετά το κλείδωμα
          document.getElementById("b").hidden=false;
      }
      function myF(){//Καλούμενη συνάρτηση που περιέχει τις άλλες δύο
          lock();
          unlock();
      }
    
   </script>
         <?php 
         }//end-if-attempt 

 }//end-if-nested
            
     else {//αν δεν είναι τίποτα από τα δύο σωστό. 
      $_SESSION["z"]=1;
       header("Location:http://localhost/Viva/registration.php"); //Μετάβαση στην σελίδα εγγραφής 
            exit;
          }//end-else    
         }//end-2nd-if
      }//end-1st-if
        
    if (isset($res)){// Αποδέσμευση αποτέλεσματος
 mysqli_free_result($res);}
 if (isset($ress)) {
 mysqli_free_result($ress);}
 if (isset($resf_l)) {
 mysqli_free_result($resf_l);}
 
   mysqli_close($con);//Κλείσιμο σύνδεσης
    ?>
        
     <h3>Enter your username and your password to login</h3>
     <form method="post"> <!--Ορισμός φόρμας-->
         <strong>Username:</strong> <input type="text" name="username" id="u" required><br><br>
         <strong>Password:</strong> <input type="password" name="pass" id="p" required><br>
         <p><strong>Your Attempts to login: <?php echo $_SESSION['attempts'];?>/3</strong></p>
         <input type="submit" onclick="<?php if ($_SESSION["attempts"]==="3"){ ?>myF();<?php }?>" name="login" id="l" value="Login"><br><br>
         <input id="b" type="button" onclick="location.href='http://localhost/Viva/lock.php'"value="Try Again Later" hidden>
        </form>
    </body>
    </html>

