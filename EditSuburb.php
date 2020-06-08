<?php 
  session_start();
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  //Checking for session expiry or not login (user id is empty)
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

  $cid = $_GET['cid'];

  if(isset($_POST['btnSave'])!=""){
    $SuburbName = $_POST['SuburbName'];
    
    $UpdateSQL = "UPDATE suburbs SET SuburbName='$SuburbName'
                  WHERE SuburbName=$cid";
    $result = mysqli_query($connection, $UpdateSQL);   
      
    if ($result) {   
      header("location: SuburbsAdmin.php");
    } else {
      $error="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    }
  }

  $SelectSql = "SELECT SuburbName FROM suburbs WHERE SuburbName=$cid";
  $result = mysqli_query($connection, $SelectSql);
  $row = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Update City/Suburb</title>

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
        <br>
    <div class="row">
      <h1 id="IdSuburbAdmin" align="center" style="color:#FFF"><strong>Update City/Suburb</strong></h1>
      <div class="col-sm-3">
      </div>
      <div class="panel panel-primary col-sm-6">
        <div class="panel-heading">City/Suburb Details</div>
        <p id="error" style="color:red"><?php echo $error; ?></p>
        <div class="panel-body" style="color:black">
          <form id="SuburbForm" action="" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
            <div class="form-group col-sm-12">
              <label class="label label-primary" for="IdFSuburbName">City/Suburb</label>
              <input type="text" class="form-control" id="IdFName" name="SuburbName" maxlength="35" value="<?php echo $row['SuburbName']; ?>" required autofocus>
            </div>

            <hr class="style1">

            <div class="form-group col-sm-12" align="right">
              <button type="button" name="btnCancel" class="btn btn-success" onclick="goBack();">Cancel</button>
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
  <script src="recruit.js"></script>
</body>

</html>
