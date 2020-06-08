<?php
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 
  session_start();
 
  //Checking for session expiry or not login (user id is empty)
  if(isset($_SESSION['UserId'])=="" ){
    header("location: login.php");
  }
  //Checking for user privilege
  if($_SESSION['Type'] == "Applicant" ){
    echo "<script>alert('Sorry you do not have the privilege to access this page!');</script>";
    echo "<script>window.history.back();</script>";
    exit;
  }

  require_once('dbconnect.php');

  // Delete in appointments table (from Employer Dashboard)
  if(isset($_GET['eid'])){
    $aid = $_GET['eid'];
    $DeleteSql = "DELETE FROM appointments WHERE AppointmentId=$aid";
    $callingprog = "EmployerDashboard.php";  
    $result = mysqli_query($connection, $DeleteSql);
  } 

  // Delete in appointments table
  if(isset($_GET['aid'])){
    $aid = $_GET['aid'];
    $uid = $_GET['uid'];
    $DeleteSql = "DELETE FROM appointments WHERE AppointmentId=$aid";
    $callingprog = "ApplicantAppointments.php?uid=$uid";  
    $result = mysqli_query($connection, $DeleteSql);
  } 
  // Delete in shortlist table
  if(isset($_GET['sid'])){
    $uid = $_GET['sid'];
    $cid = $_GET['comp'];
    $DeleteSql = "DELETE FROM shortlist WHERE CompanyCode=$cid AND ApplicantId=$uid";
    $callingprog = "Shortlist.php";            
    $result = mysqli_query($connection, $DeleteSql);
  } 

  // Delete in suburbs table
  if(isset($_GET['cid'])){
    $cid = $_GET['cid'];
    $DeleteSql = "DELETE FROM suburbs WHERE SuburbName='$cid'";
    $callingprog = "SuburbsAdmin.php";  
    $result = mysqli_query($connection, $DeleteSql);
  } 

  // Delete in skills table
  if(isset($_GET['kid'])){
    $kid = $_GET['kid'];
    $CompanyCode = $_SESSION['UserId'];
    $DeleteSql = "DELETE FROM skills WHERE SkillName='$kid' AND CompanyCode='$CompanyCode'";
    $callingprog = "SkillsAdmin.php";  
    $result = mysqli_query($connection, $DeleteSql);
  } 

  // multiple tables DELETION!!!
  // Delete user in users,applicants,workexperience,employeeskills,appointments table
  if(isset($_GET['usr'])){
    $uid = $_GET['usr'];
    $callingprog = "UserAdmin.php";  

    // 1 SQL delete did not work
    /*$DeleteSql = "DELETE a.*, b.*, c.*, d.*, e.*
                  FROM users as a, applicants as b, appointments as c, employeeskills as d, workexperience as e
                  WHERE a.UserId = b.UserId
                  AND b.UserId = c.ApplicantId
                  AND c.ApplicantId = d.UserId
                  AND d.UserId = e.UserId
                  AND a.UserId = $uid;";
    */

    //$DeleteSql = "DELETE FROM users WHERE UserId=$uid";
    $DeleteSql[0] = "DELETE FROM appointments WHERE ApplicantId=$uid";
    $DeleteSql[1] = "DELETE FROM workexperience WHERE UserId=$uid";
    $DeleteSql[2] = "DELETE FROM employeeskills WHERE UserId=$uid";
    $DeleteSql[3] = "DELETE FROM applicants WHERE UserId=$uid";
    $DeleteSql[4] = "DELETE FROM users WHERE UserId=$uid";
    
    foreach ($DeleteSql as $sql) {
      // turnoff auto-commit
      mysqli_autocommit($connection, FALSE);
      mysqli_begin_transaction($connection);

      $result = mysqli_query($connection, $sql);

      if(!$result){
          //transaction rolls back
          echo "Error: " . mysqli_error($connection)."<br>";
          mysqli_rollback($connection);
          echo "transaction rolled back";
          exit;
      }else{
        echo "SQL command: ".$sql." --> Successful! <br>";
      }
    
    }

    //transaction is committed
    mysqli_commit($connection);
    echo "Database transaction was successful";  
  } 

  // multiple tables DELETION!!!
  // Delete company in mailtemplate,searchcriteria,searchresults,appointments,shortlist,skills,companies and users table
  if(isset($_GET['cmp'])){
    $uid = $_GET['cmp'];
    $callingprog = "UserAdmin.php";  
    //$DeleteSql = "DELETE FROM users WHERE UserId=$uid";
    //$result = mysqli_query($connection, $DeleteSql);

    $DeleteSql[0] = "DELETE FROM mailtemplate WHERE CompanyCode=$uid";
    $DeleteSql[1] = "DELETE FROM searchcriteria WHERE CompanyCode=$uid";
    $DeleteSql[2] = "DELETE FROM searchresults WHERE CompanyCode=$uid";
    $DeleteSql[3] = "DELETE FROM appointments WHERE CompanyCode=$uid";
    $DeleteSql[4] = "DELETE FROM shortlist WHERE CompanyCode=$uid";
    $DeleteSql[5] = "DELETE FROM skills WHERE CompanyCode=$uid";
    $DeleteSql[6] = "DELETE FROM companies WHERE UserId=$uid";
    $DeleteSql[7] = "DELETE FROM users WHERE UserId=$uid";
    
    foreach ($DeleteSql as $sql) {
      // turnoff auto-commit
      mysqli_autocommit($connection, FALSE);
      mysqli_begin_transaction($connection);

      $result = mysqli_query($connection, $sql);

      if(!$result){
          //transaction rolls back
          echo "Error: " . mysqli_error($connection)."<br>";
          mysqli_rollback($connection);
          echo "transaction rolled back";
          exit;
      }else{
        echo "SQL command: ".$sql." --> Successful! <br>";
      }
    
    }

    //transaction is committed
    mysqli_commit($connection);
    echo "Database transaction was successful";  
  } 

  if($result){
    header("location: $callingprog");
  }else{
    echo "Error: ".mysqli_error($connection);
  }
?>

