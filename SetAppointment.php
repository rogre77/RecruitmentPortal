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
  if($_SESSION['Type'] != "Employer" ){
    echo "<script>alert('Sorry you do not have the privilege to access this page!');</script>";
    echo "<script>window.history.back();</script>";
    exit;
  }
  require_once('dbconnect.php');

  $error = "";
  $uid = intval($_GET['uid']);
  $CompanyCode = $_SESSION['UserId'];

  $SelectSql = "SELECT UserId, FirstName, LastName, Email FROM users WHERE UserId=$uid";
  $query1 = mysqli_query($connection, $SelectSql);

  if ($query1) {
    $row1 = mysqli_fetch_assoc($query1);
  } else {
    $error="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
  }

  $SelectSql = "SELECT CompanyName FROM companies WHERE UserId=$CompanyCode";
  $query2 = mysqli_query($connection, $SelectSql);
  $row2 = mysqli_fetch_assoc($query2);
    
  if(isset($_POST['BtnSave'])!=""){
    $JobReference = ""; 
    $Description = $_POST['Description'];
    $Location = $_POST['Location'];
    //$Remarks = $_POST['Remarks'];
    $DateCreated = date('Y-m-d');
    $Creator = $CompanyCode;
    $Date = $_POST['Date'];
    $Time = $_POST['Time'];
    $Status = "New";  
    
    $InsertSQL = "INSERT INTO appointments (CompanyCode, ApplicantId, JobReference, Description, Location, DateCreated, Creator, Date, 
                    Time, Status) 
                  VALUES ('$CompanyCode', '$uid', '$JobReference', '$Description', '$Location', '$DateCreated', '$Creator', '$Date', '$Time', 
                    '$Status')";
    $result = mysqli_query($connection, $InsertSQL);   
      
      if ($result) {
        header('location: ApplicantAppointments.php?uid='.$uid);
      } else {
        $error="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
      }    
  }

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Create an Appointment</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-theme.css" rel="stylesheet">

  <!-- siimple style -->
  <link href="assets/css/style.css" rel="stylesheet">

  <link href="recruit.css" rel="stylesheet">
  <!-- =======================================================
    Theme Name: Siimple
    Theme URL: https://bootstrapmade.com/free-bootstrap-landing-page/
    Author: BootstrapMade
    Author URL: https://bootstrapmade.com
  ======================================================= -->
  <style>
    
  </style>
  <script>

  </script>
</head>
<body>
  <!-- Fixed navbar -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div id="UserDtl" align="right">You are logged in as <span><?php echo $_SESSION["User"]; ?></span></div>
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        <a class="navbar-brand" href="home.php">GradForce</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-left">
          <li><a href="home.php">Home</a></li>
          <li><a href="SearchCandidate.php">Search Candidate</a></li>
          <li><a href="Shortlist.php">Shortlist</a></li>
          <li><a href="SkillsAdmin.php">Skills</a></li>        
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" href="#">Register</a>
                <div class="dropdown-menu" aria-labelledby="dropdown01">
                  <a id="regapp" class="dropdown-item" href="RegisterApplicant.php">Applicant</a> <br>
                  <a id="regemp" class="dropdown-item" href="RegisterEmployer.php">Employer</a>
                </div>
            </li>  
            <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
  </div>
  
  <div class="container-fluid">
    <h1 id="IdsrchResultHdr" align="center" style="color:#FFF"><strong>Create an Appointment</strong></h1>
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8">
      <div class="panel panel-primary">
        <div class="panel-heading">Set Appointment</div>
        <div class="panel-body" style="color:black">

          <div class="row" >
            <form id="AppointmentForm" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
            <div class="form-group">
              <label class="control-label col-sm-2">Name: </label>
              <div class="col-sm-10">
                <input type="text" class="form-control data" id="IdName" name="Name" value="<?php echo $row1['FirstName']." ".$row1['LastName']; ?>" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">Description:</label>
              <div class="col-sm-10">
                <input type="text" class="form-control data" id="IdDesc" name="Description" placeholder="e.g. First Interview" value="" required>
              </div>
            </div> 
              
            <div class="form-group">
              <label class="control-label col-sm-2">Location</label>
              <div class="col-sm-10">
                <input type="text" class="form-control data" id="IdLocation" name="Location" placeholder="Queens Street, Auckland CBD" value="" required>
              </div>
            </div>               

            <div class="form-group">
              <label class="control-label col-sm-2">Select a date:</label>
              <div class="col-sm-3">
                <input type="date" class="form-control data" id="IdDate" name="Date" value="" required>
              </div>
              <label class="control-label col-sm-1">Time:</label>
              <div class="col-sm-3">
                <input type="time" class="form-control data" id="IdTime" name="Time" value="" required>
              </div>
            </div>
            <div class="col-sm-12">
              <p id="error" style="color:red"><?php echo $error; ?></p>
              <hr class="style1">
            </div>
          <div class="col-sm-12" align="right">
            <button type="button" name="BtnCancel" value="Cancel" form="AppointmentForm" class="btn btn-success" 
                    onclick="goBack();">Cancel</button>  
            <button type="submit" name="BtnSave" value="Save" form="AppointmentForm" class="btn btn-success" 
                    onclick="return ValidateAppointment();">Save</button>   
          </div>         
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-2"></div>      
  </div>
  </div>
    
  <div id="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <p class="copyright">&copy; Siimple Theme</p>
        </div>
        <div class="col-md-6">
          <div class="credits">
              <!--
              All the links in the footer should remain intact.
              You can delete the links only if you purchased the pro version.
              Licensing information: https://bootstrapmade.com/license/
              Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Siimple
              -->
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="recruit.js"></script>    
  </body>

</html>
