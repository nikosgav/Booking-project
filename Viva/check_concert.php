<?php
session_start(); //Αρχή συνόδου
 $id=$_SESSION["id"];
if(!isset($_SESSION["id"])) { ?>

<script>alert("Login Required!");</script>

    <?php }//end-if----isset ?>

<!DOCTYPE HTML>  
<html>
<head>
     <title>Book A Ticket</title>
<style>

  h3{
    font-family: initial;
    border-bottom:2px solid darkred;
    width:25%;
  }
  
 /*---Κανόνες για σχηματισμό πινάκων---*/
  
  table,th,td{
    font-family: sans-serif;
    border: 2px solid black;
    border-collapse:collapse;
    padding:10px;
    text-align:center;
    width:10px;
   border-spacing:18px;
}

table {
    margin-top:-240px; 
    margin-left:420px;
    width:15%;
   
}
/*---Κανόνας για σχηματισμό αρχικών κελιών(επικεφαλίδες)---*/
#th{ background-color:darkkhaki; }
td { background-color: beige;}

.button{
    margin-left:750px;
     text-align:center;
      font-family: serif;
     padding:5px;
     font-size:18px;
     border: chocolate solid 4px;
    background-color: oldlace;
    width:12%;
    box-shadow: 0 9px #999;
    transform: translateY(2px);
    box-shadow: 0 5px #666;
    border-radius:7px;
     }
</style>
</head>
<body>  

<?php
////--------Πρώτο Μέρος κώδικα για Εμφάνιση Πινάκων και επιλογή συναυλίας-----//
$con = mysqli_connect("localhost","root","");
    if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
    die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
    
    mysqli_select_db($con,"test1"); //Επιλογή βάσης test1
    mysqli_set_charset($con,'utf8');
    
   //---Ερώτημα για εισαγωγή ονομάτων στην επιλογή συναυλίας---//
    $sql_c="SELECT band_name FROM concerts";
    
    //---Ερώτημα για εμφάνιση πίνακα---//
    $sql_conc="SELECT * FROM concerts";
   
  //---Εκτέλεση ερωτημάτων και ορισμός μεταβλητής για τα πεδία του πίνακα---//
   $res_c=mysqli_query($con,$sql_c);
   $res_cοnc=mysqli_query($con, $sql_conc);
   $row_c= mysqli_fetch_assoc($res_c);
   $row_show_conc= mysqli_fetch_array($res_cοnc,MYSQLI_BOTH);
   $n= mysqli_num_fields($res_cοnc);
    
 ////--------Δεύτερο μέρος Κώδικα για Επεξεργασία και Οριστικοποίηση Κράτησης------////
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        
     $seats=$_POST["seats"];
     $cname=$_POST["cname"];
    
     
       if(isset($_POST["diazoma"])) {
           
           $diazoma=$_POST["diazoma"];
            
            }//end-if---diazoma
           else
           { $diazoma="NO";   }//end-else---diazoma
       
     
    $sql_sur="SELECT id,surname FROM customers WHERE id='$id'";
    $sql_p="SELECT * FROM concerts WHERE band_name='$cname'";
    
  
    ///--------1o SELECT--------///
    $res_sur=mysqli_query($con, $sql_sur);
    $row_sur= mysqli_fetch_array($res_sur,MYSQLI_BOTH);
    ///---------2o SELECT-------///
    $res_p=mysqli_query($con, $sql_p);
    $row_p= mysqli_fetch_array($res_p,MYSQLI_BOTH);
    //---------3. Επιλογή τιμών από τα πεδία------------//
    $price=$row_p["ticket_price"];
    $bname=$row_p["band_name"];
    $surname=$row_sur["surname"];
    $remaining_tickets=$row_p["remaining_tickets"];
    $date=$row_p["date"];
    
       $cost=$seats*$price;//---Τελικό κόστος---//
    /////----------------------------------------/////
    ///-------Αν το υπόλοιπο επαρκεί και τα εισιτήρια απομένουν τότε ενημερώνεται 
    // ο πόνακας κρατήσεων, ο πίνακας συναυλιών και φυσικά το υπόλοιπο του πελάτη-----//
    if($cost<=$_SESSION["balance"] && $remaining_tickets>=$seats){ 
         
    $sql_book="INSERT INTO bookings(custid,surname,band_name,diazoma,seats,date,cost) VALUES ('$id','$surname','$cname','$diazoma',$seats,'$date','$cost')";
    $sql_buy="UPDATE concerts SET remaining_tickets=remaining_tickets-$seats WHERE band_name='$cname'";
    $sql_buy_bal="UPDATE customers SET balance=balance-$cost WHERE id='$id'";
    
      $res_book=mysqli_query($con, $sql_book);
      $res_buy=mysqli_query($con, $sql_buy);
      $res_buy_bal=mysqli_query($con, $sql_buy_bal);
 //-----Αν τα παραπάνω ισχύουν ταυτόχρονα τότε εμφανίζεται κατάλληλο μήνυμα----//
      
      if($res_book && $res_buy && $res_buy_bal){ ?>
        <script> 
     alert("Booking Confirmed!");
      </script> 
    <?php  }//end-if-res_book 
    //Περίπτωση σφάλματος αν δεν υπάρχουν κρατήσεις---///
    else { echo mysqli_error($con); }//end-else
  
        
       }//end-if-cost
      
       else { ?>
        <script> 
     alert("Not Enough Remaining or No more Tickets Left!");
      </script> 
       <?php }//end------else----if-- 
      
    
    
    
    }//end-if-----REQUEST

 

  
    ?>
     <!-- Στην παρακάτω φόρμα επιλέγει ο χρήστης την συναυλία που επιθυμεί,
καθώς και τον αριθμό των θέσεων για κάθε συναυλία και αν το συνολικό πόσο της
κράτησης δεν ξεπερνάει το υπόλοιπο της προπληρωμένης κάρτας του,τότε
πραγματοποιείται επιτυχώς η κράτηση και εμφανίζεται σχετικό μήνυμα-->

<form method="post">
    
    <h3>Please Select the Concerts and the Seats</h3>
    <strong>Concert:</strong>
    <select name="cname">
        <?php
  //--------------Μέρος κώδικα για εμφάνιση πινάκων------------//   
  //----Λήψη ονομάτων συναυλιών από τον πίνακα concerts για επιλογή συναυλίας----//
        
        while ($row_c) { ?>
        
    <option><?=$row_c["band_name"]; ?></option> 
  
    <?php $row_c= mysqli_fetch_assoc($res_c); 
    
    }//end-while ?>
   
    </select><br><br>
    
    <strong>Ticket Type: <select id="type-ticket" name="typetick" onchange="myF();">
  <option value="1">Standing</option>
   <option value="2" selected>Sitting</option>
      </select ></strong><br><br>
        
      <strong id="str">Diazoma: </strong><select id="diaz"  name="diazoma" required>
        <option>Up</option>
        <option>Down</option>
    </select><br><br>
    
    <strong>Seats: </strong> <input type="number" min="1" max="10" name="seats" required><br>
    <br><br>
 <input type="submit" name="sbmt"  value="Check">

</form>
     <!----Τέλος φόρμας επιλογής συναυλίας---->
     <script>
         //---Συνάρτηση για επιλογή τύπων εισητηρίου----//
         function myF(){
         var x= document.getElementById("type-ticket");
         var tick = x.options[x.selectedIndex].value;
         
      if (tick==="1"){
          document.getElementById("diaz").disabled=true;
          
      }//end-if
      
      else {
            document.getElementById("diaz").disabled=false;
           
        }//end-else
        
         }//end-func
     </script>
     <!-----Πίνακας εμφάνισης συναυλιών----->
   <table>
        <thead>
            <tr><!----Αρχή πρώτης γραμμής(Επικεφαλίδας)---->
                <th id="th">Concert&nbsp;Id</th>
                <th id="th">Band&nbsp;Name</th>
                <th id="th">Concert&nbsp;Date</th>
                <th id="th">Place</th>
                <th id="th">Doors&nbsp;Open</th>
                <th id="th">Ticket&nbsp;Price&nbsp;(&#8364)</th>
                <th id="th">Remaining&nbsp;Tickets</th>
           </tr><!----Τέλος πρώτης γραμμής(Επικεφαλίδας)---->
        </thead>
       <tbody> 
   <!--Όσο υπάρχει επόμενη γραμμή στα αποτελέσματα
  δημιούργησε μια γραμμή πίνακα html-->
            <?php while($row_show_conc){ ?>
            <tr>
                <?php 
 //Εστω n ο αριθμός των πεδίων,τότε δημιουργούμε μια δομή
 // επανάληψης και εισάγονται δεδομένα στον πίνακα
    
             for($i=0;$i<$n;$i++){ ?>
            
             <td><?=$row_show_conc[$i];?><?php echo'&nbsp';?></td>
            
           <?php } ?><!--Τέλος for-->
           </tr>
         <?php 
    //----Βήμα για λήψη επόμενης γραμμής από τον πίνακα----//
    $row_show_conc= mysqli_fetch_array($res_cοnc,MYSQLI_BOTH);
            }//end-while 
            ?> 
   </table><br>
     <button class="button" onclick="location.href='myprofile.php'">Back to MyProfile</button>
           <?php  
 if (isset($res_c)){
mysqli_free_result($res_c); } //Αποδέσμευση αποτελεσμάτων

if (isset($res_conc)){
mysqli_free_result($res_conc);}

mysqli_close($con); //Κλείσιμο Σύνδεσης
            
            ?>
  </body>
</html>



