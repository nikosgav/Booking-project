<?php
session_start();
$id=$_SESSION["id"];
?>

<html>
    <title>Charge Page</title>
    <head>
    <body> 
    <style>
        body{
            background-color: beige;
            background:url("money.jpg") no-repeat;
            background-position:center;
        }
        h3{
            text-align:center;
            border: 2px solid green;
            border-radius:6px;
            background-color: ghostwhite;
            width:22%;
            padding:6px;
        }
        amount{
            border:2px solid black;
            padding:3px;
            }
  
        </style>
    </head>
    
     <?php 
      $con = mysqli_connect("localhost","root","","test1");
         if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
     die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
     mysqli_set_charset($con,'utf8');//set charset=utf8

    if ($_SERVER["REQUEST_METHOD"]=="POST") {
        
     $amount=trim($_POST["amount"]);
     
    //Εκτελούμε το ερώτημα χρησιμοποιώντας σύνδεση των πινάκων της βάσης,
//εμφανίζοντας το id και το balance(υπόλοιπο) του πελάτη.Αν το id του πίνακα πελατών είναι ίδιο
//με το id με το id του πίνακα login(Αρα ο χρήστης είναι ο ίδιος) τότε μεταβάλλουμε το ποσό
  //σύμφωνα με το ποσό που επέλεξε να προσθέσει. 
     
   $sql_s="SELECT balance FROM customers WHERE id='$id'";
   $result= mysqli_query($con,$sql_s);//Εκτέλεση ερωτήματος και ορισμός μεταβλητών συνόδων για κατάλληλο 
   //χειρισμό.
   $row_sel= mysqli_fetch_assoc($result);
   $count_r= mysqli_num_rows($result);
   
 
   if($count_r>0 && $amount >=0)  {//Αν υπάρχουν γραμμές στο αποτέλεσμα τότε..
       
       //--------Ενημέρωση ποσού πελάτη--------//
    $sql_u="UPDATE customers SET balance=balance+$amount WHERE id='$id'";
    
   //-------Εκτέλεση ερωτημάτων-----//
   $res_u= mysqli_query($con, $sql_u);
   $count_u= mysqli_affected_rows($con);
    
      if ($count_u>0) 
        {
   ///-----Αν υπάρχουν γραμμές που επηρεάστηκαν από το ερώτημα τότε πηγαίνουμε στην σελίδα προφίλ του χρήστη.-----///
        $_SESSION["money"]=1;//---Μεταβλητή "δείκτης" όπως και οι προηγούμενες----//
         header("Location:http://localhost/Viva/myprofile.php");
         
        }//end-if--count_u
    }//end-if-count_r for select
    
  }//end-1st-if -REQUEST
  
  if (isset($result)){
mysqli_free_result($result); } //Αποδέσμευση αποτελεσμάτων

mysqli_close($con); //Κλείσιμο Σύνδεσης
  
  
  
  
 ?>
        <br>
        <h3>Please Insert the Amount to Charge</h3><br>
        <form name="charge" method="post">
            <strong> Amount:</strong> <input type="number" name="amount" min="0" required><br><br><br>
       <input type="submit" value="Charge Card"><br><br>
        </form>
       </body>
</html>

