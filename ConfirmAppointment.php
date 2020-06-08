<?php
  session_start();
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  //Checking for session expiry (user id is empty)
  if( isset($_SESSION['UserId'])=="" ){
    header("location: login.php");
  }
  //Checking for user privilege
  if($_SESSION['Type'] != "Applicant" ){
    echo "<script>alert('Sorry you do not have the privilege to access this page!');</script>";
    echo "<script>window.history.back();</script>";
    exit;
  }
  require_once('dbconnect.php');

  $error = "";
  $uid = $_SESSION['UserId'];
  $aid = intval($_GET['aid']);
  $action = $_GET['o']; 
  if ($action=="a") {
    $Status="Accepted";
    $PrevStatus="New";
    $ModDate = date("Y-m-d H:i:s");
  } 
  if ($action=="d") {
    $Status="Declined";
    $PrevStatus="New";
    $ModDate = date("Y-m-d H:i:s");
  }
  if ($action=="u") {
    $Status="New";
    $PrevStatus="";
    $ModDate = "";
  }

  $UpdateSQL = "UPDATE appointments SET Status='$Status', PrevStatus='$PrevStatus', Modified='$ModDate', ChangedBy='$uid' 
                 WHERE AppointmentId='$aid'";
  $result = mysqli_query($connection, $UpdateSQL);   
      
  if ($result) {
    header('location: UserDashboard.php');
  } else {
    echo "Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    exit;
  }

?>
