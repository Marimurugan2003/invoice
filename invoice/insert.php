<?php
session_start();
$conn=mysqli_connect("localhost","root","","invoice");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$product=$_POST['product'];
$billno=$_POST['billno'];
$qty=$_POST['qty'];
$price=$_POST['price'];
$gst=$_POST['gst'];
$total=$_POST['finalTotal'];
$_SESSION['billno'] = $billno;
$user="INSERT into table_list(product,billno,qty,price,gst,total) values('$product','$billno','$qty','$price','$gst','$total')";
if(mysqli_query($conn,$user)){
   echo "Insert Successfully";
}
?>