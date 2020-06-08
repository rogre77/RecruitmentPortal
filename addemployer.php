<?php 
  session_start();

  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  require_once('dbconnect.php');
  $error = '';

  if(isset($_POST) & !empty($_POST)){
    $Email = $_POST['Email'];
    $FirstName = mysqli_real_escape_string($connection,$_POST['FirstName']);
    $LastName = mysqli_real_escape_string($connection,$_POST['LastName']);
    $Password = md5($_POST['Password1']);
    $Phone = $_POST['Phone'];
    $Position = mysqli_real_escape_string($connection,$_POST['Position']);
    $Status = "Enabled";
    $Type = "Employer";
    $LastLogin = "";
      
    $CompanyName = mysqli_real_escape_string($connection,$_POST['CompanyName']);  
    $Website = mysqli_real_escape_string($connection,$_POST['Website']);  
    $Address = mysqli_real_escape_string($connection,$_POST['Address']);
    $Position = mysqli_real_escape_string($connection,$_POST['Position']);
        
    $SqlCommand = "INSERT INTO users (Email, Type, FirstName, LastName, Password, Phone, Position, LastLogin, Status) 
                  VALUES ('$Email', '$Type', '$FirstName', '$LastName', '$Password', '$Phone', '$Position', '$LastLogin', '$Status' )";
    $res = mysqli_query($connection, $SqlCommand);
    
    if($res){
      $SelectSql = "SELECT UserId from users where Email='$Email'";
      $res = mysqli_query($connection, $SelectSql);
      $row = mysqli_fetch_assoc($res);
      $UserId = $row['UserId'];
     
      $SqlCommand = "INSERT INTO companies (UserId, CompanyName, Website, Address) 
                    VALUES ('$UserId', '$CompanyName', '$Website', '$Address')";
      $res = mysqli_query($connection, $SqlCommand);

      if(!$res){
        echo "Error description: " . mysqli_error($connection);
        exit;
      }
      
      header("location: login.php"); 
      
  }
  }
?>