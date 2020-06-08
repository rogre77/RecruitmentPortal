<?php
    session_start(); //starting the session

     $error='';
     if(isset($_POST['submit']))
    {
        
      if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
    {
     //your site secret key
    $secret = '6Lfork0UAAAAAAifxgBOUkUx5fz3cAzl9rhE6XZD';
    
    //get verify response data
    //*$verify = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    
    //getting JSON
    //*$response = json_decode($verify);
    
    
    if($response->success)
    {
        checkuser();
    }
    
    else
    {
        //*$error = "Google reCAPTCHA verification failed. please try again";
        checkuser();
    }
        }
        else
        {
        //*$error = "Please check recaptcha box";
        checkuser();    
        }
}

function checkuser() {
  global $error;
    
  if(empty($_POST['email']) || empty($_POST['password'])) {
    $error = "Username or Password is Invalid";
  } else {
    //Define $user and $pass
    $email=$_POST['email'];
    $password=md5($_POST['password']);
    //Establishing Connection with server by passing server_name, user_id and pass as a parameter
    $conn = mysqli_connect("localhost", "root", "");
    //Selecting Database
    $db = mysqli_select_db($conn, "git702");
    //sql query to fetch information of registerd user and finds user match.
    $query = mysqli_query($conn, "SELECT UserId, FirstName, LastName, Type, Status FROM users WHERE Password='$password' AND Email='$email'");
    $rows = mysqli_num_rows($query);
        
    if($rows == 1) {
      $row = mysqli_fetch_assoc($query);
      $_SESSION["UserId"] = $row["UserId"];  
      $_SESSION["User"] = $row["FirstName"]." ".$row["LastName"];
      $_SESSION["Type"] = $row["Type"];
            
      if ($row["Status"] == "Disabled") {
        $error = "This user has been disabled";
      } else {
        $datetime = date('Y-m-d H:i:s');
        $UpdateSql = "UPDATE users SET LastLogin='$datetime' WHERE Email='$email'";
        $res = mysqli_query($conn, $UpdateSql);
              
        if( $_SESSION["Type"] == "Applicant") {
          header("Location: UserDashboard.php"); /* Redirect to applicant home page */ 
        } else {
          if( $_SESSION["Type"] == "Employer") {  
            header("Location: EmployerDashboard.php"); /* Redirect to employer home page */ 
          } else {
            header("Location: home.php"); /* Redirect to Admin page */    
          }
        }
      }
    } else {
      $error = "Username or Password is Invalid";
    }
      
    mysqli_close($conn); // Closing connection
  }
}
?>