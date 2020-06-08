<?php
  session_start();
  $EmailAdrs = $_GET['m'];
  require_once('dbconnect.php');
  $rowcount = 0;

  $SqlCommand = "SELECT * FROM users where Email='$EmailAdrs'";
  $query = mysqli_query($connection, $SqlCommand);

  if($query){
    $rowcount=mysqli_num_rows($query);
  } else {
    $fmsg = "Error description: " . mysqli_error($connection);
    echo $fmsg ;
  }
  echo $rowcount;

?>
