<?php
session_start(); //Αρχή συνόδου
$id=$_SESSION["id"];
?>
<!DOCTYPE HTML>  
<html>
<head>
     <title>Cancel Booking</title>
<style>
    /*-------CSS Rules----------*/
    body{
        background-color: navajowhite;
    }
  #head{
    font-family: initial;
    border-bottom:2px solid brown;
    width:32%;
    
  }
  
 /*---Κανόνες για σχηματισμό πινάκων---*/
  
  table,th,td{
    font-weight:bold;
    font-family:cursive;
    border: 2px solid black;
    border-collapse:collapse;
    padding:10px;
    text-align:center;
    width:10px;
    border-spacing:15px;
}

table {
    margin-top:-50px; 
    margin-left:400px;
   
}

.button{
    margin-left:530px;
     text-align:center;
     font-family: monospace;
     padding:5px;
     font-size:18px;
     border: darkred solid 4px;
     background-color: oldlace;
     width:17%;
    box-shadow: 0 9px #999;
    transform: translateY(2px);
    box-shadow: 0 5px #666;
    border-radius:7px;
     }
      #msg{
    margin-left:570px;
    margin-top:60px;
    color:red;
     font-family: cursive;
    }
/*---Κανόνας για σχηματισμό αρχικών κελιών(επικεφαλίδες)---*/
#th{ background-color:thistle; }
td { background-color: beige;}


</style>
</head>
<body>  

<?php
////--------Πρώτο Μέρος κώδικα για Εμφάνιση Πινάκων και επιλογή κράτησης προς ακύρωση-----//
$con = mysqli_connect("localhost","root","");
    if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
    die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
    
    mysqli_select_db($con,"test1"); //Επιλογή βάσης test1
    mysqli_set_charset($con,'utf8');//Επιλογή σέτ χαρακτήρων
    
    
     $sql_books="SELECT * FROM bookings WHERE custid='$id'";
     $res_books=mysqli_query($con, $sql_books);
     $row_books= mysqli_fetch_array($res_books,MYSQLI_BOTH);
     //------Αν υπάρχουν κρατήσεις τότε εμφανίζεται ο πίνακας κρατήσεων----//
     
     if(mysqli_num_rows($res_books)>0) {
         
    //Ερώτημα για εμφάνιση κωδικού κράτησης
   $sql_bno="SELECT bookid FROM bookings WHERE custid='$id'";
   //Ερώτημα για εμφάνιση πίνακα κρατήσεων
  //Αν υπάρχουν κρατήσεις εμφανίζεται ο πίνακας αλλιώς εμφανίζεται το μηνυμα
   //No Bookings Found!
 
     //---Εκτέλεση ερωτημάτων---//
   $res_bno=mysqli_query($con, $sql_bno);
    ///------Δημιουργία γραμμών αποτελεσμάτων------///
   $row_bno= mysqli_fetch_array($res_bno,MYSQLI_BOTH);
   
//----Αριθμός πεδίων αποτελέσματος για δημιουργία πίνακα html----///
   $num= mysqli_num_fields($res_books);
   
    
 ////--------Δεύτερο μέρος Κώδικα για Επεξεργασία και Οριστικοποίηση Ακύρωσης Κράτησης------////
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        
       $bnum=$_POST["bookno"];
           //-----Ερώτημα για εύρεση τιμής κρατήσης----//
   $sql_show_price="SELECT cost FROM bookings WHERE bookid='$bnum'";
    $res_show_price=mysqli_query($con, $sql_show_price);
    $row_show_price= mysqli_fetch_array($res_show_price,MYSQLI_BOTH);
    $cost_return=$row_show_price["cost"];
     
    
    $sql_p="UPDATE customers SET balance=balance+$cost_return WHERE id='$id'";
    $res_sql_p= mysqli_query($con, $sql_p);
    $affected_rows= mysqli_affected_rows($con);
    
 ////------Αν το υπόλοιπο έχει μεταβληθεί ΤΟΤΕ διαγράφεται και η αντίστοιχη κράτηση------///
    
    if($affected_rows>0){ 
 //---Ερώτημα για διαγραφή κράτησης από τον πίνακα.(διαγραφή γραμμής πίνακα)----//
        
     $sql_delete="DELETE FROM bookings WHERE bookid='$bnum'";
      $res_sql_delete= mysqli_query($con, $sql_delete);
     $affected_rows_del= mysqli_affected_rows($con);
     
     
         if($affected_rows_del>0){ ?>
    
  <!----Αν η γραμμή διαγραφεί επιτυχώς τότε εμφανίζεται αντίστοιχο μήνυμα---->
    <script>alert("Booking Canceled!")</script>
        
     <?php  
     
         }//end-if---delete-row  
         else { echo mysqli_error($con); }//end----else-2st-if
   
    }//end-if---affected_rows
    
     else { echo mysqli_error($con); }//end-----else----1st-if
  
   }//end---------if----------REQUEST
 
     
      ?>
<!--Στην παρακάτω φόρμα επιλέγει ο χρήστης τον κωδικό συναυλίας που επιθυμεί
να ακυρώσει και στην συνέχεια αν όλα πραγματοποιηθούν επιτυχώς 
επιστρέφεται το αντίστοιχο ποσό στην προπληρωμένη κάρτα του και διαγράφεται η
αντίστοιχη κράτηση. ------>

<form method="post">
    
    <h3 id='head'>Please Select the Booking you like to Cancel </h3>
    <strong>Book Number:</strong>
    <select name="bookno">
        <?php
  //---------------Μέρος κώδικα για εμφάνιση πινάκων-------------//   
  //------Λήψη κωδικών κρατήσεων από τον πίνακα bookings για επιλογή κράτησης προς ακύρωση-----//
        while($row_bno) { ?>
        
    <option><?=$row_bno["bookid"];?></option> 
  
    <?php $row_bno= mysqli_fetch_array($res_bno,MYSQLI_BOTH); 
    }//end-while ?>
   
    </select><br><br><!----Τέλος πρώτου select---->
    
 <input type="submit" name="sbmt"  value="Cancel Booking">

</form>
     <!----Τέλος φόρμας επιλογής κωδικού κράτησης προς ακύρωση---->
 
     <!-------Πίνακας εμφάνισης κρατήσεων πελάτη------->
   <table>
        <thead>
            <tr><!------Αρχή πρώτης γραμμής(Επικεφαλίδας)------>
             <th id="th">BookId</th>
                <th id="th">CustId</th>
                <th id="th">Surname&nbsp;</th>
                <th id="th">Concert&nbsp;Name</th>
                <th id="th">Diazoma</th>
                <th id="th">Seats</th>
                <th id="th">Concert&nbsp;Date</th>
                <th id="th">Charge</th>
                
           </tr><!------Τέλος πρώτης γραμμής(Επικεφαλίδας)------>
        </thead>
       <tbody> 
<!-----Όσο υπάρχει επόμενη γραμμή στα αποτελέσματα δημιούργησε μια γραμμή πίνακα html------->
      <?php while($row_books){ ?>
            <tr><!------Αρχή γραμμής πίνακα------->
                <?php 
 //Εστω n ο αριθμός των πεδίων,τότε δημιουργούμε μια δομή 
 //επανάληψης και εισάγονται δεδομένα στον πίνακα όσο υπάρχει επόμενη γραμμή.
    
             for($i=0;$i<$num;$i++){ ?>
            
                <td><?=$row_books[$i]?></td>
            
           <?php } ?><!----Τέλος for---->
           </tr><!----Τέλος γραμμής πίνακα----->
         <?php 
    //--------Βήμα για λήψη επόμενης γραμμής από τον πίνακα---------//
    $row_books= mysqli_fetch_array($res_books,MYSQLI_BOTH);
            }//----end-while----- 
            
           ?>
   </table><!-----Τέλος πίνακα----->  
     <?php   }
                else {
      echo "<h3 id='msg'>No Bookings Found!</h3>"; }//end-else---no--bokkings ?>
   <br>
 <button class="button" onclick="location.href='myprofile.php'">Back to My Profile</button>

     <?php      
///--------Αποδέσμευση αποτελεσμάτων------///
   if (isset($res_bno)){
mysqli_free_result($res_bno); } 

if (isset($res_books)){
mysqli_free_result($res_books);}

if (isset($res_show_price)){
mysqli_free_result($res_show_price);}


///----Κλείσιμο Σύνδεσης------///
mysqli_close($con);
      
      ?>
  </body>
</html><!-----Τέλος σελίδας---->

