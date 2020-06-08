<?php
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 
  
  session_start();
  $sessData = !empty($_SESSION['sessData'])?$_SESSION['sessData']:'';
  if(!empty($sessData['status']['msg'])){
      $statusMsg = $sessData['status']['msg'];
      $statusMsgType = $sessData['status']['type'];
      unset($_SESSION['sessData']['status']);
  }
  $error="";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link href="recruit.css" rel="stylesheet">

  <title>Reset Password</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-theme.css" rel="stylesheet">

  <!-- siimple style -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
    Theme Name: Siimple
    Theme URL: https://bootstrapmade.com/free-bootstrap-landing-page/
    Author: BootstrapMade
    Author URL: https://bootstrapmade.com
  ======================================================= -->

</head>

<body>

  <!-- Fixed navbar -->
  <div class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
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
                  <a id="regemp" class="dropdown-item" href="regUser.php">Employer</a>
                </div>
            </li> 
            <li><a href="login.php">Login</a></li>
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
  </div>

  <div id="header"  style="color:black">
    <div class="container">
      <div class="col-md-3"></div>
        <div class = "col-md-6  justified" style="background-color:white; " >
          <h4>Reset Your Account Password</h4>
          <?php echo !empty($statusMsg)?'<p class="'.$statusMsgType.'">'.$statusMsg.'</p>':''; ?>
          <br><br>
          <form action="UserAccount.php" method="post">
            <div class="form-label-group">
              <input type="password" name="password"  class="form-control" placeholder="Password" required autofocus> <br>
              <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div>
            <br>
            <div class="col-md-12" align="right">
              <input type="hidden" name="fp_code" value="<?php echo $_REQUEST['fp_code']; ?>"/>
              <button type="button" name="btnCancel" class="btn btn-success" onclick="goBack();">Cancel</button>
              <button type="submit" name="btnResetSubmit" class="btn btn-success" onclick="" >Submit</button>   
            </div>
            
          </form>
            <br><br><br>
        </div>

      <div class="col-md-3"></div>    
    </div>
  </div>
    
  <div id="footer">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <p class="copyright">&copy; 2018</p>
        </div>
        <div class="col-md-6">
          <div class="credits">
            <!--
              All the links in the footer should remain intact.
              You can delete the links only if you purchased the pro version.
              Licensing information: https://bootstrapmade.com/license/
              Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/buy/?theme=Siimple
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            -->
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
