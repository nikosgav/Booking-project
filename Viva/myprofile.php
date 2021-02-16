<?php
session_start(); //Αρχή συνόδου
?>
<!DOCTYPE HTML>  
<html>
<head>
     <title>My Profile</title>
     
     <?php
     if (isset($_SESSION['x']))
    { ?><!--Μήνυμα επιτυχημένης σύνδεσης-->
    <script>alert("Successful Login");</script>
        <?php unset($_SESSION['x']);
               $_SESSION["x1"]=1;} ?>
        
         <?php
     if (isset($_SESSION["money"]))
         
    { ?> <!--Μήνυμα για επιτυχημένη "φόρτιση" κάρτας-->
    <script>alert("Card Charged Successfully");</script>
        <?php unset($_SESSION["money"]);  } 
        ?>
        
        <meta charset="UTF-8">
<body>
<h3 id="hello">Welcome <?php //Εμφάνιση ονόματος χρήστη
if (isset($_SESSION['username'])){echo $_SESSION['username'];} ?>! </h3>
    
<!--Εκτύπωση κενών-->
<?php echo str_repeat('&nbsp',10); 

?>

<!--Εμφάνιση Υπολοίπου--->
<h3 id="balance">Account Balance: <?php
if(isset($_SESSION["balance"])){ 
    echo $_SESSION["balance"],'&nbsp','&#8364'; }
         ?></h3>

<!--Εμφάνιση ημερομηνίας και ώρας-->
 <?php date_default_timezone_set('Europe/Athens'); 
    echo str_repeat('&nbsp',20); ?>
   
   <h3 id="date"> <!--Εμφάνιση ημερομηνίας και ώρας--> 
  <?php echo '&nbsp',date('l   d  M  Y'),'&nbsp','&nbsp',date('G:i'); ?> </h3>
   <br><br>
   <button class="button" id="logout" onclick="location.href='logout.php'">Logout</button>
<style>

body
{
    margin:auto;
    background-color: beige;
}
    #hello{
          display:inline-block;
          text-align: center;
           padding:5px;
           font-family: monospace;
           border: indianred solid 4px;
           width:18%;
           background-color: mintcream;
           border-radius: 8px;
        }
        #date{
            text-align:center;
            margin-top:0px;
            float:right;
            padding:3px;
            font-family:serif;
            border: darkslategray solid 3px;
            width:18%;
           background-color: aliceblue ;
           border-radius:5px;
        }
        
        #balance
        {
            display: inline-block;
            text-align:center;
            font-weight: bold;
           padding:5px;
           font-family: sans-serif;
           border: mediumblue solid 3px;
           background-color: oldlace; 
        }
        
table { margin-top:20px; }

#th{ background-color: palegoldenrod; }

     #books{
         margin-left:550px;
         margin-top:40px;
         text-align: center;
         display: block;
         padding:5px;
         font-family: monospace;
         border: #996600 solid 4px;
         width:15%;
         background-color: wheat; 
        }
        
table,th,td{
    font-family: sans-serif;
    margin-left:280px;
    border: 2px solid black;
    border-collapse:collapse;
    padding:10px;
    text-align:center;
    width:10px;
   border-spacing:15px;
}

.container {
     
     position:relative;
     margin-left:300px;
   display:inline; 
   padding:5px;
}

.button{
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
     
 .button:active {
 box-shadow: 0 2px;
 transform: translateY(4px);
}
     
  #msg{
    margin-left:570px;
    margin-top:60px;
    color:red;
     font-family: cursive;
    }
    #logout{
        width:6%;
        margin-top:-40px;
        float:right;
        background-color: ivory;
        border:4px red solid;
        border-radius:5px;
    }
    td{background-color:white;}

</style>

</head>
    
    <h3 id="books">Bookings:</h3>
<?php


 $con = mysqli_connect("localhost","root","");
    if (!$con)
    {  //Έλεγχος σύνδεσης με την βάση
    die('Could not connect: ' . mysqli_connect_error());
    }//2nd-if
    
    mysqli_select_db($con,"test1"); //Επιλογή βάσης test1
    mysqli_set_charset($con,'utf8');//Set charset='utf8'
    
     $myid=$_SESSION["id"];
     
    ////-------Ερώτημα για εμφάνιση υπολοίπου πελάτη---------////
    $sql_balance="SELECT balance FROM customers WHERE id='$myid'";
    $res_balance=mysqli_query($con, $sql_balance);
    $row_balance= mysqli_fetch_assoc($res_balance);
    $_SESSION["balance"]=$row_balance["balance"];
    
    ////-------Ερώτημα για εμφάνιση πίνακα κρατήσεων--------////
    $sql_show_books="SELECT * FROM bookings WHERE custid='$myid'";
    $res_show_books=mysqli_query($con, $sql_show_books);
    $row_show_books= mysqli_fetch_array($res_show_books,MYSQLI_BOTH);
    
     function blank(){//Συνάρτηση που δημιουργεί κενά
                echo str_repeat("&nbsp",3);
                
            }//end-func
    
    //Αν υπάρχουν κρατήσεις τότε εμφάνιση αυτών
    if (mysqli_num_rows($res_show_books)>0) 
        {
        //------Αριθμός Πεδίων Πίνακα------//
        $fields= mysqli_num_fields($res_show_books);
    
?> <!--Δημιουργία πίνακα(html) με τις κρατήσεις του πελάτη.
   Αν υπάρχουν κρατήσεις τότε δημιουργείται ο παρακάτω πίνακας 
   αλλιώς εμφανίζεται το μήνυμα 'No Bookings Found'.-->
    <table>
        <thead>
            <tr>
                <th id="th">BookId</th>
                <th id="th">CustId</th>
                <th id="th">Surname&nbsp;</th>
                <th id="th">Concert&nbsp;Name</th>
                <th id="th">Diazoma</th>
                <th id="th">Seats</th>
                <th id="th">Concert&nbsp;Date</th>
                <th id="th">Charge</th>
                
           </tr><!--Τέλος αρχικής γραμμής-->
        </thead>
        
        <tbody> <!--Όσο υπάρχει επόμενη γραμμή στα αποτελέσματα 
                   δημιούργησε μια γραμμή πίνακα html.-->
            <?php while($row_show_books){ ?>
            <tr>
                <?php 
      //'Εστω fields ο αριθμός των πεδίων,τότε δημιουργούμε μια
      //δομή επανάληψης και εισάγονται κάθε φορά δεδομένα στον πίνακα
      // που δημιουργήσαμε πριν.
             for($i=0;$i<$fields;$i++){ ?>
            
                <td><?=$row_show_books[$i]?></td>
            
           <?php } ?><!--Τέλος for-->
           </tr>
         <?php  
    $row_show_books= mysqli_fetch_array($res_show_books,MYSQLI_BOTH);//---Βήμα για λήψη επόμενης γραμμής από τον πίνακα---//
            }//end-while
            
           
            ?>
        </tbody>
    </table><br><br>
       <?php }//end-if------num--rows 
   
   else {
       echo "<h3 id='msg'>No Bookings Found!</h3>"; 
   }//end-else
    ?>
<div class="container">
    <!-----Κουμπιά για αντίστοιχη επιλογή---->
    <button class="button" onclick="location.href='check_concert.php'">Book A Ticket</button><?php blank(); ?> 
    <button class="button" onclick="location.href='overview.php'">Overview</button><?php blank(); ?> 
    <button class="button" onclick="location.href='charge.php'">Charge Card</button><?php blank();?> 
    <button class="button" onclick="location.href='cancel.php'" style="color:red">Cancel Booking</button>
</div>
      
 <?php
 //-----Αποδέσμευση αποτελεσμάτων-----//
if (isset($res_show_books)){
mysqli_free_result($res_show_books); } 

//---Κλείσιμο Σύνδεσης---//
mysqli_close($con); 

   ?>
     
</body>
</html>
