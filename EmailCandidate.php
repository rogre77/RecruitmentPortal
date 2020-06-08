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
    echo "Error description0: " . mysqli_error($connection);
  }

  $SelectSql = "SELECT users.Email, companies.CompanyName FROM users,companies WHERE users.UserId=companies.UserId 
                AND companies.UserId=$CompanyCode";
  $query2 = mysqli_query($connection, $SelectSql);
  $row2 = mysqli_fetch_assoc($query2);
    
  if(isset($_POST['save'])!=""){
    //Delete previous record 
    $DeleteSQL = "DELETE FROM mailtemplate WHERE CompanyCode='$CompanyCode'";
    $result = mysqli_query($connection, $DeleteSQL);  

    if (!$result) {
      echo "Error description1: " . mysqli_error($connection);
    }
    
    $Subject = $_POST['Subject'];
    $Message = $_POST['Message'];
    
    $InsertSQL = "INSERT INTO mailtemplate (CompanyCode, Subject, Message) 
                  VALUES ('$CompanyCode', '$Subject', '$Message')";
    $result = mysqli_query($connection, $InsertSQL);   
      
      if (!$result) {
        echo "Error description2: " . mysqli_error($connection);
      }    
  }


  if(isset($_POST['send'])!=""){
    //echo "This is your message:".$_POST['Message'];
    $the_sender = "GradForce Web Recruitment Portal Auto-Mailer";
    $the_subject = $_POST['Subject'];
    $the_message = $_POST['Message'];
    $the_tomail = $row1['Email'];
    $the_toname = $row1['FirstName']." ".$row1['LastName'];
    $the_cc = $_POST['cc'];
    $preview = str_replace("%Applicant",$the_toname,$_POST['Message']);
    $preview = str_replace("%CompanyName",$row2['CompanyName'],$preview);
    $preview = str_replace("%CompanyEmail",$row2['Email'],$preview);
    
    $the_message = nl2br($preview);

    include("mailer/mail.php");
    if ($error=="") {
      header("location: ShortlistDetail.php?uid=".$uid);
    }
  }  

  $SelectSql = "SELECT * FROM mailtemplate WHERE CompanyCode=$CompanyCode";
  $query3 = mysqli_query($connection, $SelectSql);
  $row3 = mysqli_fetch_assoc($query3);

  if($row3['CompanyCode']=="") {
    $SelectSql = "SELECT * FROM mailtemplate WHERE CompanyCode=0";
    $query3 = mysqli_query($connection, $SelectSql);
    $row3 = mysqli_fetch_assoc($query3);    
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

  <title>Contact Candidate by EMail</title>

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
    <h1 id="IdsrchResultHdr" align="center" style="color:#FFF"><strong>Contact Candidate by Email</strong></h1>
    <div class="col-sm-2">
    </div>
    <div class="col-sm-8">
      <div class="panel panel-primary">
        <div class="panel-heading">Automail Template</div>
        <div class="panel-body" style="color:black">

          <div class="row " >
            <form class="form-horizontal" id="EmailForm" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
            <div class="form-group">
              <label class="control-label col-sm-2">To: </label>
              <div class="col-sm-9">
                <input type="text" class="form-control data" id="IdTo" name="To" value="<?php echo $row1['FirstName']." ".$row1['LastName']." (".$row1['Email'].")"; ?>" disabled>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-2">CC:</label>
              <div class="col-sm-9">
                <input type="text" class="form-control data" id="Idcc" name="cc" value="">
              </div>
            </div> 
            <div class="form-group">
              <label class="control-label col-sm-2">Subject</label>
              <div class="col-sm-9">
                <input type="text" class="form-control data" id="IdSubject" name="Subject" value="<?php echo 'You have been shortlisted for a possible job position by '.$row2['CompanyName'].'.'; ?>">
              </div>
            </div>               
            
            <div class="form-group">
              <label class="control-label col-sm-2">Message</label>
              <div class="col-sm-9">
                <textarea type="text" class="form-control data" id="IdMessage" name="Message" rows="10"><?php echo $row3['Message']; ?>
               </textarea>
              </div>
            </div>
            <input type="hidden" class="form-control data" id="AName" name="AName" value="<?php echo $row1['FirstName'].' '.$row1['LastName']; ?>">
            <input type="hidden" class="form-control data" id="CompName" name="CompName" value="<?php echo $row2['CompanyName']; ?>">
            <input type="hidden" class="form-control data" id="CompMail" name="CompMail" value="<?php echo $row2['Email']; ?>">

          <p style="color:red" class="error"><?php echo $error; ?></p>
    
          <hr class="style1">

          <div class="col-sm-12" align="right">
            <button type="button" name="back" value="back" form="EmailForm" class="btn btn-success" onclick="location.href='ShortlistDetail.php?uid=<?php echo $uid; ?>';">Back</button>   
            <button type="submit" name="save" value="save" form="EmailForm" class="btn btn-success">Save Template</button>  
            <button type="button" name="preview" id="myBtn" class="btn btn-success" >Preview</button>
            <button type="submit" name="send" value="send" form="EmailForm" class="btn btn-success">Send</button>   
          </div>         
          </form>
        </div>
      </div>
    </div>
    <div class="col-sm-2">
    </div>      
  </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="previewMail" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" style="color:black">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="exampleModalLongTitle">Email Preview</h4>
        </div>
        <div id="txt" class="modal-body">
          <?php echo $preview; ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
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
<script>
$(document).ready(function(){
    $("#myBtn").click(function(){
      msg = document.getElementById("IdMessage").value;
      aname = document.getElementById("AName").value;
      cname = document.getElementById("CompName").value;
      cmail = document.getElementById("CompMail").value;
      prv = msg.replace(/%Applicant/g, aname);
      prv = prv.replace(/%CompanyName/g, cname);
      prv = prv.replace(/%CompanyEmail/g, cmail);
      prv = prv.replace(/(?:\r\n|\r|\n)/g, '<br>');
      document.getElementById("txt").innerHTML = prv;
        $("#previewMail").modal();
    });
});
</script>  
  </body>

</html>
