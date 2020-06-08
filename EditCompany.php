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
  if($_SESSION['Type'] != "Administrator" ){
    echo "<script>alert('Sorry you do not have the privilege to access this page!');</script>";
    echo "<script>window.history.back();</script>";
    exit;
  }

  require_once('dbconnect.php');
  $error = '';

  $uid = $_GET['cmp'];

  if(isset($_POST['btnSave'])!=""){
    $Status = $_POST['Status'];
    
    $UpdateSQL = "UPDATE users SET Status='$Status'
                  WHERE UserId=$uid";
    $result = mysqli_query($connection, $UpdateSQL);   
      
    if ($result) {   
      header("location: CompanyAdmin.php");
    } else {
      $error="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    }
  }

  $SelectSql = "SELECT users.FirstName, users.LastName, users.Position, users.Email, users.Phone, users.Status, 
                  companies.CompanyName, companies.Website
                FROM users,companies WHERE users.UserId=$uid and users.UserId=companies.UserId";
  $result = mysqli_query($connection, $SelectSql);
  if ($result) {
    $row = mysqli_fetch_assoc($result);
  } else {
    $error="Error: ". mysqli_error($connection)." Please check your entry. Thank you!";
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
  <title>Update Company User Status</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-theme.css" rel="stylesheet">

  <!-- siimple style -->
  <link href="assets/css/style.css" rel="stylesheet">
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
          <li><a href="CompanyAdmin.php">Companies</a></li>
          <li><a href="UserAdmin.php">Applicants</a></li>
          <li><a href="SuburbsAdmin.php">Suburbs</a></li>
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
    </div>
  </div>
  
  <div class="container">
    <div class="row">
      <h1 id="IdUserAdminHdr" align="center" style="color:#FFF"><strong>Update Company Status</strong></h1>
      <div class="col-sm-2">
      </div>
      <div class="panel panel-primary col-sm-8">
        <div class="panel-heading">Company Details</div>
          <div class="panel-body" style="color:black">
            <form id="form" action="" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
              <div class="form-group col-sm-6">
                <label class="label label-primary">Company Name</label>
                <span class="form-control"><?php echo $row['CompanyName']; ?></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="label label-primary">Website</label>
                <span class="form-control"><?php echo $row['Website']; ?></span>
              </div>           
              <div class="form-group col-sm-6">
                <label class="label label-primary">Contact Person</label>
                <span class="form-control"><?php echo $row['FirstName'].' '.$row['LastName']; ?></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="label label-primary">Job Title</label>
                <span class="form-control"><?php echo $row['Position']; ?></span>
              </div>
              <div class="form-group col-sm-6">
                <label class="label label-primary">Email</label>
                <span class="form-control"><?php echo $row['Email']; ?></span>
              </div>      
              <div class="form-group col-sm-3">
                <label class="label label-primary">Phone</label>
                <span class="form-control"><?php echo $row['Phone']; ?></span>
              </div> 
              <div class="form-group col-sm-3">
                <label for="IdStatus"> Status:</label> <br>
                <input type="radio" name="Status" value="Enabled" id="IdStatus" <?php echo ($row['Status']=='Enabled')?'checked':'' ?>> Enabled 
                <input type="radio" name="Status" value="Disabled" id="IdStatus" <?php echo ($row['Status']=='Disabled')?'checked':'' ?>> Disabled
              </div>   
              <div class="col-sm-12" id="error" style="color:red"><?php echo $error; ?></div>
            <!--
             .
            <div class="form-group col-md-6">
              <label class="label label-primary" for="IdPassword1">Password</label>
              <input type="password" class="form-control" id="IdPassword1" name="Password1" value="<//?php echo $row['Password']; ?>" data-minlength="6" required>
            </div>
            <div class="form-group col-md-6">
              <label class="label label-primary" for="IdPassword2">Verify Password</label>
              <input type="password" class="form-control" id="IdPassword2" name="Password2" value="<//?php echo $row['Password']; ?>" data-match="#Password1" data-match-error="Whoops, these don't match" required>
            </div>  
            -->
            <hr class="style1">

            <div class="form-group col-sm-9">
            </div>
            <div class="form-group col-sm-3">
              <button type="button" class="btn btn-success" onclick="location.href='CompanyAdmin.php';">Cancel</button>
              <button type="submit" name="btnSave" class="btn btn-success" onclick="" >Save</button>   
            </div>   
          </form>
        </div>
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
</body>

</html>
