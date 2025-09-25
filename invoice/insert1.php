<?php
$conn=mysqli_connect("localhost","root","","invoice");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$cname=$_POST['cname'];
$billno=$_POST['billno'];
$date=$_POST['date'];
$billtype=$_POST['billtype'];
$total=$_POST['total'];
$totalGst=$_POST['totalGst'];
$billAmount=$_POST['billAmount'];
$user="INSERT into table_value(cname,billno,date,billtype,total,totalGst,billAmount) values('$cname','$billno','$date','$billtype','$total','$totalGst','$billAmount')";
if(mysqli_query($conn,$user)){
   echo "Insert Successfully";
}
header("Location:table.php");
?>
