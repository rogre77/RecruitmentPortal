<?php 
  session_start();
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  require_once('dbconnect.php');
  $SelectSql = "SELECT SuburbName from suburbs";
  $query1 = mysqli_query($connection, $SelectSql);

  $SelectSql = "SELECT SkillName from skills";
  $query2 = mysqli_query($connection, $SelectSql);

  if( isset($_SESSION['UserId'])=="" ){
    $UserDtl = "";
  } else {
    $UserDtl = "You are logged in as ".$_SESSION["User"];
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
  <title>Employer Registration</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-theme.css" rel="stylesheet">

  <!-- siimple style -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="project.css"/>
    <script>
    </script> 
</head>

<body>

  <!-- Fixed navbar -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div id="UserDtl" align="right"><?php echo $UserDtl; ?></div>
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
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" href="#">Register</a>
            <div class="dropdown-menu" aria-labelledby="dropdown01">
              <a id="regapp" class="dropdown-item" href="RegisterApplicant.php">Applicant</a> <br>
              <a id="regemp" class="dropdown-item" href="RegisterEmployer.php">Employer</a>
            </div>
          </li>  
          <?php if($UserDtl=="") { ?>
              <li><a href="login.php">Login</a></li>
          <?php } else { ?>
              <li><a href="logout.php">Logout</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
 
  <div class = "container" id="cont">
    <br>
    <h1 id="IdAppReg" align="center" style="color:#FFF"><strong>Employer Registration</strong></h1>
    <div class="col-sm-2"></div>    
    <div class="panel panel-primary col-sm-8">
      <div class="panel-heading">Employer Profile Creation</div>
      <div id="MyProfile" class="panel-body" style="color:black">
        
      <form id="RegForm" action="addemployer.php" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
         <fieldset>
          <legend>Company Details:</legend>
           <br>
           <div class="form-group col-sm-12">
              <label id="lblCompanyName" class="label label-primary">Company Name</label>
              <input type="text" class="form-control" id="IdCompanyName" name="CompanyName" required autofocus>
            </div>
            <div class="form-group col-sm-12">
              <label id="lblWebsite" class="label label-primary">Website</label>
              <input type="text" class="form-control" id="IdWebsite" name="Website" required>
            </div>
            <div class="form-group col-sm-12">
              <label id="lblAddress" class="label label-primary">Address</label>
              <input type="text" class="form-control" id="IdAddress" name="Address" required>
            </div>      
          .
          <legend>Contact Person:</legend>
          <div class="form-group col-sm-6">
            <label id="lblFirstName" class="label label-primary">First Name</label>
            <input type="text" class="form-control" id="IdFirstName" name="FirstName" placeholder="First name" required>
          </div>
           <div class="form-group col-sm-6">
            <label id="lblLastName" class="label label-primary">Last Name</label>
            <input type="text" class="form-control" id="IdLastName" name="LastName" placeholder="Last name" required>
          </div>           
          <div class="form-group col-md-6">
            <label id="lblPosition"  class="label label-primary">Job Title</label>
            <input type="text" class="form-control" id="IdPosition" name="Position" placeholder="Job Title" required>
          </div>           
          <div class="form-group col-md-6">
            <label id="lblPhone"  class="label label-primary">Phone</label>
            <input type="text" class="form-control" id="IdPhone" name="Phone" placeholder="Mobile Number" required>
          </div>
          <div class="form-group col-sm-12">
            <label id="lblEmail"  class="label label-primary">Email</label>
            <input type="email" class="form-control" id="IdEmail" name="Email" aria-describedby="emailHelp" placeholder="Enter email address" onBlur="CheckMail(this.value)" required>
            <small id="emailHelp" class="form-text text-muted" style="color:red"></small>
          </div>           
          <div class="form-group col-md-6">
            <label id="lblpsw1"  class="label label-primary">Password</label>
            <input type="password" class="form-control" id="IdPassword1" name="Password1" placeholder="Password" data-minlength="6" required>
            <small id="psw1Help" class="form-text text-muted">Passwords should not be less than 8</small>
          </div>
          <div class="form-group col-md-6">
            <label id="lblpsw2"  class="label label-primary">Verify Password</label>
            <input type="password" class="form-control" id="IdPassword2" name="Password2" placeholder="Confirm" data-match="#Password1" data-match-error="Whoops, these don't match" required>
            <small id="psw2Help" class="form-text text-muted">Passwords should not be less than 8</small>
          </div>         
          
          </fieldset>
          
          <p id="error" style="color:red"></p>          
          <hr class="style1">
          
          <div class="form-group col-sm-9">
          </div>
          <div class="form-group col-sm-3">
            <button type="button" class="btn btn-success" onclick="location.href='home.php';">Cancel</button>
            <button type="submit" class="btn btn-success" onclick="return ValidateEmployer();" >Submit</button>   
          </div>
          
        </form>     
           
      </div>
    </div>
  </div>
  <br>
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
