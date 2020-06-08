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

  $error="";
  $uid = intval($_GET['uid']);
  $CompanyCode = $_SESSION['UserId'];

  $SelectSql = "SELECT users.UserId, users.FirstName, users.LastName, users.Email, users.Phone, applicants.Suburbs, applicants.Degree,  
                applicants.DegreeGraduated, applicants.PostGraduate, applicants.PGGraduated, applicants.Masters, applicants.MastersGraduated, applicants.Doctorate, applicants.DoctorateGraduated, applicants.ResidencyStatus, applicants.WorkAvailability, applicants.Interests, applicants.Status, shortlist.Remarks, shortlist.StatusWithUs, shortlist.PrevStatWithUs, applicants.PrevStatus 
                FROM users,applicants,shortlist WHERE users.UserId=$uid AND shortlist.CompanyCode=$CompanyCode AND users.UserId=applicants.UserId AND users.UserId=shortlist.ApplicantId";
  $query1 = mysqli_query($connection, $SelectSql);
    
  if ($query1) {
    $row1 = mysqli_fetch_assoc($query1);
  } else {
    //echo "Error description: " . mysqli_error($connection);
    $error = "Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
  }
  
  $SelectSql = "SELECT * FROM workexperience WHERE UserId=$uid";
  $result1 = mysqli_query($connection, $SelectSql);

  $SelectSql = "SELECT * FROM employeeskills WHERE UserId=$uid";
  $result2 = mysqli_query($connection, $SelectSql);

  if(isset($_POST['btnHire'])!=""){
    if ($row1['Status'] == "Hired") {
      $NewStat = $row1['PrevStatWithUs'];
      $CurrStat = "";
    } else {
      $NewStat = "Hired";
      $CurrStat = $row1['StatusWithUs'];
    }    
    $UpdateSql = "UPDATE shortlist SET StatusWithUs='$NewStat', PrevStatWithUs='$CurrStat' WHERE CompanyCode='$CompanyCode' AND ApplicantId='$uid'";
        
    $result = mysqli_query($connection, $UpdateSql);
    if($result){
      if ($row1['Status'] == "Hired") {
        $NewStat = $row1['PrevStatus'];
        $CurrStat = "";
      } else {
        $NewStat = "Hired";
        $CurrStat = $row1['Status'];
      }
      $UpdateSql = "UPDATE applicants SET Status='$NewStat', PrevStatus='$CurrStat' WHERE UserId='$uid'";
        
      $result = mysqli_query($connection, $UpdateSql);      
      if($result){
        header('location: Shortlist.php');
      } else {
        $error = "Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
      }
    } else {
      $error = "Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    }
  }

  if(isset($_POST['btnSave'])!=""){
    $Remarks = $_POST['Remarks'];
    $StatusWithUs = $_POST['StatusWithUs'];

    $UpdateSql = "UPDATE shortlist SET Remarks='$Remarks', StatusWithUs='$StatusWithUs' WHERE CompanyCode='$CompanyCode' AND ApplicantId='$uid'";
        
    $result = mysqli_query($connection, $UpdateSql);
    if($result){
      header('location: Shortlist.php');
    } else {
      $error = "Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    }
  }

  if(isset($_POST['btnEmail'])!=""){      
    header('location: EmailCandidate.php?uid='.$uid);
  }

  if(isset($_POST['btnAppointment'])!=""){      
    header('location: ApplicantAppointments.php?uid='.$uid);
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
  <title>Shortlist Candidates Details</title>

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
    <h1 id="IdsrchResultHdr" align="center" style="color:#FFF"><strong>Shortlisted Applicant Details</strong></h1>
    <div class="col-sm-3">
    </div>
    <div class="col-sm-6">
      <div class="panel panel-primary">
        <div class="panel-heading">Applicant's Profile</div>
        <div class="panel-body" style="color:black">

          <div id="withborder" class="row " >
            <legend style="color:blue">Personal Information:</legend>
            <div class="form-group col-sm-3">
              <label class="label label-primary">First Name</label><br>
              <span class="data"><?php echo $row1['FirstName']; ?></span>
            </div>
            <div class="form-group col-sm-3">
              <label class="label label-primary">Last Name</label><br>
              <span class="data"><?php echo $row1['LastName']; ?></span>
            </div> 
            <div class="form-group col-sm-6">
              <label class="label label-primary">Email</label><br>
              <span class="data"><?php echo $row1['Email']; ?></span>
            </div> 
            <div class="form-group col-md-3">
              <label class="label label-primary">Phone</label><br>
              <span class="data"><?php echo $row1['Phone']; ?></span>
            </div> 
            <div class="form-group col-md-3">
              <label class="label label-primary">City/Suburbs</label><br>
              <span class="data"><?php echo $row1['Suburbs']; ?></span>
            </div>
            <div class="form-group col-sm-6">
              <label class="label label-primary"> Status:</label> <br>  
              <span class="data"><?php echo $row1['Status']; ?></span>
            </div>
            
            <form id="ShortListDetailForm" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
              <div class="form-group col-sm-8">
                <label class="label label-primary">Remarks</label>
                <input type="text" class="form-control data" id="IdRemarks" name="Remarks" value="<?php echo $row1['Remarks']; ?>" placeholder="e.g. For project # 18" autofocus <?php echo ($row1['Status']=='Hired')?'disabled':'enabled' ?>>
              </div>
              <div class="col-sm-4">
                <label class="label label-primary">Status With Us:</label>
                <input type="text" class="form-control data" id="IdStatus" name="Status" value="<?php echo $row1['Status']; ?>"  <?php echo ($row1['Status']=='Hired')?'disabled':'enabled' ?> style="display:<?php echo ($row1['Status']=='Hired')?'inline':'none' ?>">
                <select class="form-control" id="IdStatusWithUs" name="StatusWithUs" style="display:<?php echo ($row1['Status']=='Hired')?'none':'inline' ?>">
                  <option value="First Interview" <?php echo ($row1['StatusWithUs']=="First Interview")?'selected':'' ?>>First Interview</option>
                  <option value="Second Interview" <?php echo ($row1['StatusWithUs']=="Second Interview")?'selected':'' ?>>Second Interview</option>
                  <option value="Third Interview" <?php echo ($row1['StatusWithUs']=="Third Interview")?'selected':'' ?>>Third Interview</option>
                  <option value="Fourth Interview" <?php echo ($row1['StatusWithUs']=="Fourth Interview")?'selected':'' ?>>Fourth Interview</option>
                  <option value="Final Interview" <?php echo ($row1['StatusWithUs']=="Final Interview")?'selected':'' ?>>Final Interview</option>
                </select>
              </div>               
            </form>
            
          </div>    
          <br>
          <div id="withborder" class="row " >
            <legend style="color:blue">Educational Information:</legend>
            <?php if ($row1['Degree'] != "") {
            ?>
              <div class="form-group col-sm-9">
                <label class="label label-primary">Degree</label><br>
                <span class="data"><?php echo $row1['Degree']; ?></span>
              </div>
              <div class="form-group col-sm-3">
                <label class="label label-primary">Year Graduated</label><br>
                <span class="data"><?php echo $row1['DegreeGraduated']; ?></span>
              </div>   
            <?php
              }
            ?>

            <?php if ($row1['PostGraduate'] != "") {
            ?>
              <div class="form-group col-sm-9">
                <label class="label label-primary" >Post Graduate</label><br>
                <span class="data"><?php echo $row1['PostGraduate']; ?></span>
              </div>
              <div class="form-group col-sm-3">
                <label class="label label-primary">Year Graduated</label><br>
                <span class="data"><?php echo $row1['PGGraduated']; ?></span>
              </div>   
            <?php
              }
            ?>    

            <?php if ($row1['Masters'] != "") {
            ?>
              <div class="form-group col-sm-9">
                <label class="label label-primary" >Masters</label><br>
                <span class="data"><?php echo $row1['Masters']; ?></span>
              </div>
              <div class="form-group col-sm-3">
                <label class="label label-primary">Year Graduated</label><br>
                <span class="data"><?php echo $row1['MastersGraduated']; ?></span>
              </div>   
            <?php
              }
            ?>    

            <?php if ($row1['Doctorate'] != "") {
            ?>
              <div class="form-group col-sm-9">
                <label class="label label-primary" >Doctorate</label><br>
                <span class="data"><?php echo $row1['Doctorate']; ?></span>
              </div>
              <div class="form-group col-sm-3">
                <label class="label label-primary">Year Graduated</label><br>
                <span class="data"><?php echo $row1['DoctorateGraduated']; ?></span>
              </div>   
            <?php
              }
            ?>    
          </div>
          <br>

          <div id="withborder" class="row " >
            <legend style="color:blue">Visa Information:</legend>
            <div class="form-group col-sm-9">
              <label class="label label-primary"> Residency Status</label> <br>
              <span class="data"><?php echo $row1['ResidencyStatus']; ?></span>
            </div>
            <div class="form-group col-sm-3">
              <label class="label label-primary"> Work Availability</label><br>
              <span class="data"><?php echo $row1['WorkAvailability']; ?></span>
            </div>           
          </div>
          <br>

          <div id="withborder" class="row " >
            <legend style="color:blue">Other Information:</legend>
            <div class="form-group col-sm-12">
              <label class="label label-primary">Interests</label> <br>
              <span class="data"><?php echo $row1['Interests']; ?></span>
            </div>
          </div>
          <br>
    
          <div id="withborder" class="row " >
            <div id="IDWorkInfo">
              <legend style="color:blue">Work Experience:</legend>
              <?php
                $JobNo = 0;
                while ($rowe = mysqli_fetch_assoc($result1)) {
                  $JobNo++;
              ?>    
                <div id="withborder" class="row " >
                  <div class="form-group col-sm-12"> 
                    <h4>Job # <span><?php echo $JobNo; ?></span></h4>
                  </div>
                  <div class="form-group col-sm-9">
                    <label class="label label-primary">Company,City,Country</label><br>
                    <span class="data"><?php echo $rowe['CompanyCityCountry']; ?></span>
                  </div>
                  <div class="form-group col-sm-3">
                    <label class="label label-primary">Duration (years)</label><br>
                    <span class="data"><?php echo $rowe['Duration']; ?></span>
                  </div> 
                  <div class="form-group col-sm-12">
                    <label class="label label-primary">Title</label><br>
                    <span class="data"><?php echo $rowe['Title']; ?></span>
                  </div>
                  <div class="form-group col-sm-12">
                    <label class="label label-primary">Role</label><br>
                    <span class="data"><?php echo $rowe['Role']; ?></span>
                  </div>   
              </div>
              <?php
                }
              ?>             
            </div>    
          </div>
          <br>

          <div id="withborder" class="row " >
            <legend style="color:blue">Skills Set:</legend>  
            <!--      
            <div class="form-group col-sm-6"> 
            </div>  
            <div class="form-group col-sm-6"> 
              <h4>-------------Self Assessment-------------</h4>
            </div>     
            -->
            <?php
              $SkillCnt = 0;
              while ($rows = mysqli_fetch_assoc($result2)) {
                $SkillCnt++;
            ?>               
              <div class="col-sm-6 form-group">
                <span class="data"><?php echo $rows['SkillName']; ?></span>
              </div>
              <div class="col-sm-6 form-group data">
                <?php 
                  if($rows['ExpertLevel']=='1') {
                    echo "Beginner";
                  } else if($rows['ExpertLevel']=='2') {
                    echo "Intermediate";
                  } else {
                    echo "Advance";
                  }
                ?>
              </div>

            <?php
              }
            ?>               
          </div>
          <p id="error" style="color:red"><?php echo $error; ?></p>

          <hr class="style1">

          <div class="col-sm-12"  align="right">
            <button type="button" name="BtnCancel" value="Cancel" form="AppointmentForm" class="btn btn-success" 
                    onclick="location.href='Shortlist.php';">Back</button> 
            <button type="submit" name="btnSave" form="ShortListDetailForm" value="save" class="btn btn-success" <?php echo ($row1['Status']=='Hired')?'disabled':'enabled' ?>>Save</button>
            <button type="submit" name="btnEmail" form="ShortListDetailForm" value="search" class="btn btn-success" <?php echo ($row1['Status']=='Hired')?'disabled':'enabled' ?>>Contact by Email</button>   
            <button type="submit" name="btnAppointment" form="ShortListDetailForm" value="appointment" class="btn btn-success" <?php echo ($row1['Status']=='Hired')?'disabled':'enabled' ?>> Appointments</button>   
            <button type="submit" name="btnHire"  form="ShortListDetailForm" value="hire" class="btn btn-success" onclick="return ConfirmAction();"><?php echo ($row1['Status']=='Hired')?'Unhire':'Hire' ?></button>   
          </div>         
        </div>
      </div>
    </div>
    <div class="col-sm-3">
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
