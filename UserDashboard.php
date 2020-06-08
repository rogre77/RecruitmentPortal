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

  if( $_SESSION["Type"] == "Administrator") {
    header("Location: home.php"); /* Redirect to admin home page */ 
  } 
          
  if( $_SESSION["Type"] == "Employer") {  
    header("Location: EmployerDashboard.php"); /* Redirect to employer home page */ 
  } 

  require_once('dbconnect.php');

  $uid = $_SESSION['UserId'];
  $errorPI = "";
  $errorEI = "";

  if(isset($_POST['btnSavePI'])!=""){
    $FirstName = mysqli_real_escape_string($connection,$_POST['FirstName']);
    $LastName = mysqli_real_escape_string($connection,$_POST['LastName']);
    $Phone = $_POST['Phone'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password1'];
    $CurrentPassword = $_POST['Password3'];
    if ($Password != $CurrentPassword) {
      $Password = md5($_POST['Password1']);
    } 
    
    $Suburbs = $_POST['Suburbs'];
    $Status = $_POST['Status'];

    $UpdateSQL = "UPDATE users SET FirstName='$FirstName', LastName='$LastName', Password='$Password', Email='$Email', Phone='$Phone'  
                  WHERE UserId='$uid'";
    $result = mysqli_query($connection, $UpdateSQL);   
      
    if ($result) {
      $UpdateSQL = "UPDATE applicants SET Suburbs='$Suburbs', Status='$Status'
                    WHERE UserId='$uid'";
      $result = mysqli_query($connection, $UpdateSQL);   

      if (!$result) {
        $errorPI="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
      }   
    } else {
      $errorPI="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    } 
  }

  if(isset($_POST['btnSaveEI'])!=""){
    //echo "button:".$_POST['btnSaveEI'];
    $Degree = mysqli_real_escape_string($connection,$_POST['Degree']);
    $DegreeGYear = $_POST['DegreeGYear'];
    $PGradDesc = mysqli_real_escape_string($connection,$_POST['PGradDesc']);
    $PGradGYear = $_POST['PGradGYear'];
    $MastersDesc = mysqli_real_escape_string($connection,$_POST['MastersDesc']);
    $MastersGYear = $_POST['MastersGYear'];
    $DoctorateDesc = mysqli_real_escape_string($connection,$_POST['DoctorateDesc']);
    $DoctorateGYear = $_POST['DoctorateGYear'];

    $UpdateSQL = "UPDATE applicants SET Degree='$Degree', DegreeGraduated='$DegreeGYear', PostGraduate='$PGradDesc', PGGraduated='$PGradGYear', 
                      Masters='$MastersDesc', MastersGraduated='$MastersGYear', Doctorate='$DoctorateDesc', DoctorateGraduated='$DoctorateGYear'
                  WHERE UserId='$uid'";
    $result = mysqli_query($connection, $UpdateSQL);   
      
    if (!$result) {
      $errorEI="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    } 
  }

  if(isset($_POST['btnSaveOI'])!=""){
    //echo "button:".$_POST['btnSaveEI'];
    $Interests = mysqli_real_escape_string($connection,$_POST['Interests']);

    $UpdateSQL = "UPDATE applicants SET Interests='$Interests'
                  WHERE UserId='$uid'";
    $result = mysqli_query($connection, $UpdateSQL);   
      
    if (!$result) {
      $errorEI="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
    } 
  }

  if(isset($_POST['btnSaveWE'])!=""){
    $DeleteSql = "DELETE FROM workexperience WHERE UserId=$uid";
    $res = mysqli_query($connection, $DeleteSql); 
    if(!$res){
      echo "Error description: " . mysqli_error($connection);
      exit;
    }       
    
    $WorkNo = 0; 
    $cnt = 0;
    while ($_POST['Job'][$cnt]) {
  
      $WorkNo = $WorkNo + 1;
      //echo "Job:".$cnt." ".$WorkNo." ".$_POST['Job'][$cnt]."<br>";
      $CompanyCityCountry = mysqli_real_escape_string($connection,$_POST['Job'][$cnt]); 
      $Duration = $_POST['Duration'][$cnt]; 
      $Title = mysqli_real_escape_string($connection,$_POST['Title'][$cnt]); 
      $Role = mysqli_real_escape_string($connection,$_POST['Role'][$cnt]);

      $SqlCommand = "INSERT INTO workexperience (UserId, WorkNo, CompanyCityCountry, Duration, Title, Role) 
                    VALUES ('$uid', '$WorkNo', '$CompanyCityCountry', '$Duration', '$Title', '$Role' )";     
      $res = mysqli_query($connection, $SqlCommand);

      if(!$res){
        echo "Error description: " . mysqli_error($connection);
        exit;
      }      
      $cnt++;
    }     
  }

  if(isset($_POST['btnSaveSS'])!=""){
    $DeleteSql = "DELETE FROM employeeskills WHERE UserId=$uid";
    $res = mysqli_query($connection, $DeleteSql); 
    if(!$res){
      echo "Error description: " . mysqli_error($connection);
      exit;
    }       
    

    $cnt = 0;
    while ($cnt < 150) {
      if(!isset($_POST['Skill'][$cnt])) {
        $cnt++;
        continue;
      }  
      //echo "Skill:".$cnt." ".$_POST['Skill'][$cnt]." Level:".$_POST['Lvl'][$cnt][0]."<br>";

      $SkillName = $_POST['Skill'][$cnt];
      $ExpertLevel = $_POST['Lvl'][$cnt][0];

      $SqlCommand = "INSERT INTO employeeskills (UserId, SkillName, ExpertLevel) 
                    VALUES ('$uid', '$SkillName', '$ExpertLevel' )";     
      $res = mysqli_query($connection, $SqlCommand);

      if(!$res){
        echo "Error description: " . mysqli_error($connection);
        exit;
      }  
      $cnt++;
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

  <title>GradForce User Profile</title>

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
  <style>
.x3 {
	font-size: 25px;
	font-weight: 400;
  -webkit-text-decoration-line: underline;
  text-decoration: underline;
}
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

  <div class="container">
    <div class="row">
      <div id="sess"></div>
      <h1 id="IdUserAdminHdr" align="center" style="color:#FFF"><strong>User Dashboard</strong></h1>
      <div class="panel panel-primary">
        <div class="panel-body" style="color:black">
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#MyProfile">My Profile</a></li>
            <li>               <a data-toggle="tab" href="#MyAppointments">My Appointments</a></li>
          </ul>

          <div class="tab-content">
            <div id="MyProfile" class="tab-pane fade in active" >
              <?php
                $SelectSql = "SELECT users.FirstName, users.LastName, users.Email, users.Password, users.Phone, applicants.Suburbs, 
                          applicants.Degree, applicants.DegreeGraduated, applicants.PostGraduate, applicants.PGGraduated, applicants.Masters, 
                          applicants.MastersGraduated, applicants.Doctorate, applicants.DoctorateGraduated, applicants.ResidencyStatus, applicants.WorkAvailability, applicants.Interests, applicants.Status
                              FROM users,applicants 
                              WHERE users.UserId=$uid 
                              AND users.UserId=applicants.UserId";
                $result = mysqli_query($connection, $SelectSql);
                $row = mysqli_fetch_assoc($result);

                $SelectSql = "SELECT * FROM workexperience WHERE UserId=$uid";
                $result = mysqli_query($connection, $SelectSql);

                $SelectSql = "SELECT * FROM employeeskills WHERE UserId=$uid";
                $result1 = mysqli_query($connection, $SelectSql);

                $SelectSql = "SELECT SuburbName from suburbs";
                $query1 = mysqli_query($connection, $SelectSql);

                $SelectSql = "SELECT SkillName from skills";
                $query2 = mysqli_query($connection, $SelectSql);
              ?>
              <br><br>
              <div class="col-sm-2">
              </div>
              <div class="panel panel-primary col-sm-8">

                <div class="panel-body" style="color:black">              
                  <form id="RegForm" action="" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
                    <fieldset>
                      <div id="PersonalInfo" class="form-group col-sm-12">
                      <div>
                        <span class="x3">Personal Information:</span>
                        <button type="button" name="btnUpdatePI" class="btn btn-success btn-xs ViewObjPI" onclick="ShowHideClass('.UpdtObjPI','.ViewObjPI')"><span class="glyphicon glyphicon-edit"></span></button>
                        <button type="button" name="btnCancelPI" form="RegForm" class="btn btn-success btn-xs UpdtObjPI" style="display:none" onclick="ShowHideClass('.ViewObjPI','.UpdtObjPI')"><span class="glyphicon glyphicon-remove"></span></button>
                        <button type="submit" name="btnSavePI" class="btn btn-success btn-xs UpdtObjPI" style="display:none" onclick="return ValidatePersonalInfo();" ><span class="glyphicon glyphicon-ok"></span></button>   
                      </div>

                      <div class="form-group col-sm-5">
                        <label id="lblFirstName" class="label label-primary">First Name</label><br>
                        <span class="ViewObjPI"><?php echo $row['FirstName']; ?></span>
                        <input type="text" class="form-control UpdtObjPI" id="IdFirstName" name="FirstName" maxlength="25" value="<?php echo $row['FirstName']; ?>" style="display:none" required>
                      </div>
                      <div class="form-group col-sm-4">
                        <label id="lblLastName" class="label label-primary">Last Name</label><br>
                        <span class="ViewObjPI"><?php echo $row['LastName']; ?></span>
                        <input type="text" class="form-control UpdtObjPI" id="IdLastName" name="LastName" maxlength="25" value="<?php echo $row['LastName']; ?>" style="display:none" required>
                      </div>    
                      <div class="form-group col-md-3">
                        <label id="lblPhone" class="label label-primary">Phone</label><br>
                        <span class="ViewObjPI"><?php echo $row['Phone']; ?></span>
                        <input type="text" class="form-control UpdtObjPI" id="IdPhone" name="Phone" maxlength="15" value="<?php echo $row['Phone']; ?>" style="display:none" required>
                      </div>           

                      <div class="form-group col-sm-5">
                        <label id="lblEmail" class="label label-primary">Email</label><br>
                        <span class="ViewObjPI"><?php echo $row['Email']; ?></span>
                        <input type="email" class="form-control UpdtObjPI" id="IdEmail" name="Email" maxlength="50" aria-describedby="emailHelp" value="<?php echo $row['Email']; ?>" style="display:none" required>
                        <small id="emailHelp" class="form-text text-muted"></small>
                      </div>  
                      
                      <div class="form-group col-md-4">
                        <label id="lblSuburbs" class="label label-primary">City/Suburbs</label><br>
                        <span class="ViewObjPI"><?php echo $row['Suburbs']; ?></span>
                        <select class="form-control UpdtObjPI" id="IdSuburbs" name="Suburbs" style="display:none">
                          <?php
                          while ($row1 = mysqli_fetch_assoc($query1)) {
                          ?>    
                          <option value="<?php echo $row1['SuburbName']; ?>" <?php echo ($row1['SuburbName']==$row['Suburbs'])?'selected':'' ?>><?php echo $row1['SuburbName']; ?></option>
              
                          <?php
                          }
                          ?>
                        </select>
                      </div>                      
      
                      <div class="form-group col-sm-3">
                        <label id="lblStatus" class="label label-primary">Status</label><br>
                        <span class="ViewObjPI"><?php echo $row['Status']; ?></span>
                        <div class="UpdtObjPI" style="display:none">
                        <input type="radio" name="Status" value="Available" id="IdStatus" <?php echo ($row['Status']=='Available')?'checked':'' ?>> Available <br>
                        <input type="radio" name="Status" value="Unavailable" id="IdStatus" <?php echo ($row['Status']=='Unavailable')?'checked':'' ?>> Unavailable <br>
                        <input type="radio" name="Status" value="Hired" id="IdStatus" <?php echo ($row['Status']=='Hired')?'checked':'' ?>> Hired 
                        </div>
                      </div>  
                      
                      <div class="form-group col-sm-12">
                      </div>
          
                      <div class="form-group col-md-4">
                        <label id="lblPassword1" class="label label-primary">Password</label><br>
                        <span class="ViewObjPI"><?php echo "****************"; ?></span>
                        <input type="password" class="form-control UpdtObjPI" id="IdPassword1" name="Password1" maxlength="15" value="<?php echo $row['Password']; ?>" data-minlength="8" style="display:none" required>
                      </div>
          
                      <div class="form-group col-md-4 ViewObjPI">
                      </div>
                      <div class="form-group col-md-4 UpdtObjPI" style="display:none">
                        <label id="lblPassword2" class="label label-primary" for="IdPassword2">Verify Password</label><br>
                        <span class="ViewObjPI"><?php echo $row['Password']; ?></span>
                        <input type="password" class="form-control UpdtObjPI" id="IdPassword2" name="Password2" maxlength="15" value="<?php echo $row['Password']; ?>" data-match="#Password1" data-match-error="Whoops, these don't match" style="display:none" required>
                      </div>     
                      <input type="hidden" class="form-control " id="IdPassword3" name="Password3" value="<?php echo $row['Password']; ?>" >
                      </div>
                      <p id="errorPI" style="color:red"><?php echo $errorPI; ?></p>

                      <div class="row"></div>

                      <div id="EducationalInfo" class="form-group col-sm-12">
                      <div>
                        <span class="x3">Educational Information:</span>
                        <button type="button" name="btnUpdateEI" class="btn btn-success btn-xs ViewObjEI" onclick="ShowHideClass('.UpdtObjEI','.ViewObjEI')"><span class="glyphicon glyphicon-edit"></span></button>
                        <button type="button" name="btnCancelEI" form="RegForm" class="btn btn-success btn-xs UpdtObjEI" style="display:none" onclick="ShowHideClass('.ViewObjEI','.UpdtObjEI')"><span class="glyphicon glyphicon-remove"></span></button>
                        <button type="submit" name="btnSaveEI" class="btn btn-success  btn-xs UpdtObjEI" style="display:none" onclick="return ValidateEducInfo();" ><span class="glyphicon glyphicon-ok"></span></button>   
                      </div>
                      <div class="form-group col-sm-9">
                        <label id="lblDegree" class="label label-primary">Degree</label><br>
                        <span class="ViewObjEI"><?php echo $row['Degree']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdDegreeDesc" name="Degree" maxlength="50" value="<?php echo $row['Degree']; ?>" style="display:none">
                      </div>
                      <div class="form-group col-sm-3">
                        <label id="lblDegreeGYear" class="label label-primary">Year Graduated</label><br>
                        <span class="ViewObjEI"><?php echo $row['DegreeGraduated']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdDegreeGYear" name="DegreeGYear" maxlength="04" value="<?php echo $row['DegreeGraduated']; ?>"  style="display:none">
                      </div>   
                      <div class="form-group col-sm-9">
                        <label id="lblPostGraduate" class="label label-primary">Post-Graduate</label><br>
                        <span class="ViewObjEI"><?php echo $row['PostGraduate']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdPGradDesc" name="PGradDesc" maxlength="50" value="<?php echo $row['PostGraduate']; ?>" style="display:none">
                      </div>
                      <div class="form-group col-sm-3">
                        <label id="lblPostGraduateGYear" class="label label-primary">Year Graduated</label><br>
                        <span class="ViewObjEI"><?php echo $row['PGGraduated']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdPGradGYear" name="PGradGYear" maxlength="04" value="<?php echo $row['PGGraduated']; ?>" style="display:none">
                      </div>  
                      <div class="form-group col-sm-9">
                        <label id="lblMasters" class="label label-primary">Masters</label><br>
                        <span class="ViewObjEI"><?php echo $row['Masters']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdMastersDesc" name="MastersDesc" maxlength="50" value="<?php echo $row['Masters']; ?>" style="display:none">
                      </div>
                      <div class="form-group col-sm-3">
                        <label id="lblMastersGYear" class="label label-primary">Year Graduated</label><br>
                        <span class="ViewObjEI"><?php echo $row['MastersGraduated']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdMastersGYear" name="MastersGYear" maxlength="04" value="<?php echo $row['MastersGraduated']; ?>" style="display:none">
                      </div>  
                      <div class="form-group col-sm-9">
                        <label id="lblDoctorate" class="label label-primary">Doctorate</label><br>
                        <span class="ViewObjEI"><?php echo $row['Doctorate']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdDoctorateDesc" name="DoctorateDesc" maxlength="50" value="<?php echo $row['Doctorate']; ?>" style="display:none">
                      </div>
                      <div class="form-group col-sm-3">
                        <label id="lblDoctorateGYear" class="label label-primary">Year Graduated</label><br>
                        <span class="ViewObjEI"><?php echo $row['DoctorateGraduated']; ?></span>
                        <input type="text" class="form-control UpdtObjEI" id="IdDoctorateGYear" name="DoctorateGYear" maxlength="04" value="<?php echo $row['DoctorateGraduated']; ?>" style="display:none">
                      </div>    
                      </div>                        
                      <p id="errorEI" style="color:red"><?php echo $errorEI; ?></p>

                      <div id="VisaInfo" class="form-group col-sm-12">

                      <div>
                        <span class="x3">Visa Information:</span>
                        <button type="button" name="btnUpdateProfile" class="btn btn-success btn-xs ViewObjVI" onclick="ShowHideClass('.UpdtObjVI','.ViewObjVI')"><span class="glyphicon glyphicon-edit"></span></button>
                        <button type="button" name="btnCancelProfile" class="btn btn-success btn-xs UpdtObjVI" style="display:none" onclick="ShowHideClass('.ViewObjVI','.UpdtObjVI')"><span class="glyphicon glyphicon-remove"></span></button>
                        <button type="submit" name="btnSaveProfile" class="btn btn-success  btn-xs UpdtObjVI" style="display:none" onclick="return ValidatePasswords();" ><span class="glyphicon glyphicon-ok"></span></button>   
                      </div>
                      <div class="form-group col-sm-9">
                        <label class="label label-primary" for="IdVisa"> Residency Status</label> <br>
                        <span class="ViewObjVI"><?php echo $row['ResidencyStatus']; ?></span>
                        <div class="UpdtObjVI" style="display:none">
                        <input type="radio" name="visa" value="Holiday Visa" id="IdVisa" <?php echo ($row['ResidencyStatus']=='Holiday Visa')?'checked':'' ?>> Holiday Visa 
                        <input type="radio" name="visa" value="Student Visa" id="IdVisa" <?php echo ($row['ResidencyStatus']=='Student Visa')?'checked':'' ?>> Student Visa
                        <input type="radio" name="visa" value="Work Visa" id="IdVisa" <?php echo ($row['ResidencyStatus']=='Work Visa')?'checked':'' ?>> Work Visa
                        <input type="radio" name="visa" value="NZ Permanent Resident" id="IdVisa" <?php echo ($row['ResidencyStatus']=='NZ Permanent Resident')?'checked':'' ?>> NZ Permanent Resident
                        </div>
                      </div>
                      <div class="form-group col-sm-3">
                        <label class="label label-primary" for="IdHours"> Work Availability</label> <br>
                        <span class="ViewObjVI"><?php echo $row['WorkAvailability']; ?></span>
                        <div class="UpdtObjVI" style="display:none">
                        <input type="radio" name="hours" value="20 hours" id="IdHours" <?php echo ($row['WorkAvailability']=='Holiday Visa')?'checked':'' ?>> 20 hours <br>
                        <input type="radio" name="hours" value="Full Time" id="IdHours" <?php echo ($row['WorkAvailability']=='Holiday Visa')?'checked':'' ?>> Full Time 
                        </div>
                      </div>     
                      </div>
                      
                      <div id="OtherInfo" class="form-group col-sm-12">
                      <div>
                        <span class="x3">Other Information:</span>
                        <button type="button" name="btnUpdateOI" class="btn btn-success btn-xs ViewObjOI" onclick="ShowHideClass('.UpdtObjOI','.ViewObjOI')"><span class="glyphicon glyphicon-edit"></span></button>
                        <button type="button" name="btnCancelOI" class="btn btn-success btn-xs UpdtObjOI" style="display:none" onclick="ShowHideClass('.ViewObjOI','.UpdtObjOI')"><span class="glyphicon glyphicon-remove"></span></button>
                        <button type="submit" name="btnSaveOI" class="btn btn-success btn-xs UpdtObjOI" style="display:none" onclick="" ><span class="glyphicon glyphicon-ok"></span></button>   
                      </div>
                      <div class="form-group col-sm-12">
                        <label class="label label-primary">Interests</label><br>
                        <span class="ViewObjOI"><?php echo $row['Interests']; ?></span>
                        <input type="text" class="form-control UpdtObjOI" id="IdInterests" name="Interests" maxlength="100" value="<?php echo $row['Interests']; ?>" style="display:none">
                      </div>                      
                      </div>
                      <br>
                      
                      <div id="WorkExp" class="form-group col-sm-12">
                      <div id="IDWorkInfo" >
                        <span class="x3">Work Experience:</span>
                        <button type="button" name="btnUpdateWE" class="btn btn-success btn-xs ViewObjWE" onclick="ShowHideClass('.UpdtObjWE','.ViewObjWE')"><span class="glyphicon glyphicon-edit"></span></button>
                        <button type="submit" name="btnCancelWE" class="btn btn-success btn-xs UpdtObjWE" style="display:none" onclick="ShowHideClass('.ViewObjWE','.UpdtObjWE')"><span class="glyphicon glyphicon-remove"></span></button>
                        <button type="submit" name="btnSaveWE" class="btn btn-success btn-xs UpdtObjWE" style="display:none" onclick="" ><span class="glyphicon glyphicon-ok"></span></button>                           
                        <?php
                          $JobNo = 0;
                          while ($rowe = mysqli_fetch_assoc($result)) {
                            $JobNo++;
                        ?>    

                        <div id="<?php echo $JobNo; ?>">
                        <div class="form-group col-sm-12"> 
                          <h4 class="ViewObjWE">Job # <span><?php echo $JobNo; ?></span></h4>
                          <div class="UpdtObjWE" style="display:none">
                            <h4>Job # <span><?php echo $JobNo.'<small><a href="javascript:delJob('. $JobNo .')"> (Delete)</a></small>'; ?></span></h4>
                          </div>
                        </div>
                        <div class="form-group col-sm-9">
                          <label class="label label-primary">Company,City,Country</label><br>
                          <span class="ViewObjWE"><?php echo $rowe['CompanyCityCountry']; ?></span>
                          <input type="text" class="form-control UpdtObjWE" id="IdJob[]" name="Job[]" maxlength="100" value="<?php echo $rowe['CompanyCityCountry']; ?>" style="display:none" required>
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="label label-primary">Duration (# of years)</label><br>
                          <span class="ViewObjWE"><?php echo $rowe['Duration']; ?></span>
                          <input type="text" class="form-control UpdtObjWE" id="IdDuration[]" name="Duration[]" maxlength="05" value="<?php echo $rowe['Duration']; ?>" style="display:none" required>
                        </div> 
                        <div class="form-group col-sm-12">
                          <label class="label label-primary">Title</label><br>
                          <span class="ViewObjWE"><?php echo $rowe['Title']; ?></span>
                          <input type="text" class="form-control UpdtObjWE" id="IdTitle[]" name="Title[]" maxlength="50" value="<?php echo $rowe['Title']; ?>" style="display:none" required>
                        </div>
                        <div class="form-group col-sm-12">
                          <label class="label label-primary">Role</label><br>
                          <span class="ViewObjWE"><?php echo $rowe['Role']; ?></span>
                          <textarea class="form-control UpdtObjWE" id="IdRole[]" name="Role[]" maxlength="200" rows="3" style="display:none" required><?php echo $rowe['Role']; ?></textarea>
                        </div>   
                        <br>
                        </div>
                        <?php
                          }
                        ?>             
                      </div>     
                      <br>
                      <input type="hidden" id="Jid" value="<?php echo $JobNo; ?>">
                      <button type="button" class="btn btn-primary UpdtObjWE" onclick="newjob1()" style="display:none">Add Work Experience</button> 
                      <br>

                      <div id="IDWorkTmpl" style="display:none">

                        <div class="form-group col-sm-9">
                          <label class="label label-primary">Company,City,Country</label>
                          <input type="text" class="form-control" id="IdJob[]" name="Job[]" maxlength="100" placeholder="e.g. The Warehouse, Auckland, New Zealand">
                        </div>
                        <div class="form-group col-sm-3">
                          <label class="label label-primary">Duration (# of years)</label>
                          <input type="text" class="form-control" id="IdDuration[]" name="Duration[]" maxlength="05" placeholder="e.g. 3.5">
                        </div> 
                        <div class="form-group col-sm-12">
                          <label class="label label-primary">Title</label>
                          <input type="text" class="form-control" id="IdTitle[]" name="Title[]" maxlength="50" placeholder="e.g. Web Developer">
                        </div>
                        <div class="form-group col-sm-12">
                          <label class="label label-primary">Role</label>
                          <textarea class="form-control" id="IdRole[]" name="Role[]" maxlength="200" rows="3" placeholder="Designed and developed websites. Technical team leader."></textarea>
                        </div>   
                        <br>
                      </div>       
                      </div>
                      <br>
                      
                      <div id="SkillsSet" class="form-group col-sm-12">                      
                      <div>
                      <span class="x3">Skills Set:</span>
                      <button type="button" name="btnUpdateSS" class="btn btn-success btn-xs ViewObjSS" onclick="ShowHideClass('.UpdtObjSS','.ViewObjSS')"><span class="glyphicon glyphicon-edit"></span></button>
                      <button type="submit" name="btnCancelSS" class="btn btn-success btn-xs UpdtObjSS" style="display:none" onclick="ShowHideClass('.ViewObjSS','.UpdtObjSS')"><span class="glyphicon glyphicon-remove"></span></button>
                      <button type="submit" name="btnSaveSS" class="btn btn-success btn-xs UpdtObjSS" style="display:none" onclick="" ><span class="glyphicon glyphicon-ok"></span></button> 
                      </div>
                      <div class="UpdtObjSS" style="display:none">
                      <div class="form-group col-sm-4">
                          <label class="label label-primary" for="IdSkills">Add Skills</label>
                          <select class="form-control" id="IdSkillTbl">
                            <option selected>Choose...</option>
                            <?php
                              while ($row = mysqli_fetch_assoc($query2)) {
                            ?>    
                              <option value="<?php echo $row['SkillName']; ?>" ><?php echo $row['SkillName']; ?></option>
                            <?php
                              }
                            ?>                
                          </select>
                       </div>
                      <div class="form-group col-sm-2"> 
                        <br>
                        <button type="button" class="btn btn-primary" onclick="newskill()">Add</button>             
                      </div>   
                      </div>       
                      <div class="form-group col-sm-6 ViewObjSS"></div>
                      <div class="form-group col-sm-6"> 
                        <h4>-----------Self Assessment------------</h4>
                      </div>
                       <div class="form-group col-sm-12">
                       </div>
                      <div id="SkillsObj">
                        <?php
                          $SkillCnt = 0;
                          $SkillCnt1 = 100;
                          $SkillArray = array();
                          while ($rows = mysqli_fetch_assoc($result1)) {
                            $SkillArray[$SkillCnt] = $rows['SkillName'];
                        ?>     
                        <div id="<?php echo $SkillCnt1; ?>">
                        <div class="col-sm-6 form-group">
                          <input type="hidden" name="Skill[<?php echo $SkillCnt; ?>]" value="<?php echo $rows['SkillName']; ?>">
                          <?php echo $rows['SkillName']; ?>
                        </div>
                          
                        <div class="col-sm-6 form-group ViewObjSS">
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
                        <div class="col-sm-5 form-group UpdtObjSS" style="display:none">
                          <input type="radio" name="Lvl[<?php echo $SkillCnt; ?>][0]" value="1" id="IDLvl[<?php echo $SkillCnt; ?>][0]" <?php echo ($rows['ExpertLevel']=='1')?'checked':'' ?>> Beginner 
                          <input type="radio" name="Lvl[<?php echo $SkillCnt; ?>][0]" value="2" id="IDLvl[<?php echo $SkillCnt; ?>][0]" <?php echo ($rows['ExpertLevel']=='2')?'checked':'' ?>> Intermediate
                          <input type="radio" name="Lvl[<?php echo $SkillCnt; ?>][0]" value="3" id="IDLvl[<?php echo $SkillCnt; ?>][0]" <?php echo ($rows['ExpertLevel']=='3')?'checked':'' ?>> Advance
                        </div>
                        <div class="col-sm-1 form-group UpdtObjSS" style="display:none">
                          <a href="javascript:delSkill(<?php echo $SkillCnt1; ?>)">Del</a>
                        </div>
                        </div>
                        <?php
                            $SkillCnt++;
                            $SkillCnt1++;
                          }
                        ?>               
                       </div>
                      </div>
                      <br>
                      
                      <div id="skilltpl" style="display:none">
                        <div id="Skill">xx</div>
                      </div>

                      </fieldset>

                    </form>    
                    
                    <p id="Skillcnt1" hidden><?php echo $SkillCnt1; ?></p>
                    <p id="skillarray" hidden><?php echo implode(",", $SkillArray); ?></p>
                    <p id="error" style="color:red"></p>

                  </div>
                </div>
              </div>
            
            <div id="MyAppointments" class="tab-pane fade ">
              <?php
                $uid = $_SESSION['UserId'];

                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                  $pageno = 1;
                }
                $no_of_records_per_page = 20;
                $offset = ($pageno-1) * $no_of_records_per_page;

                require_once('dbconnect.php');

                $SelectSql = "SELECT * FROM appointments WHERE ApplicantId=$uid";
                $query = mysqli_query($connection, $SelectSql);

                if($query){
                      $total_rows=mysqli_num_rows($query);
                }else{
                      $error = "Error description: " . mysqli_error($connection);
                }

                $total_pages = ceil($total_rows / $no_of_records_per_page);

                $SelectSql = "SELECT appointments.AppointmentId, appointments.Description, appointments.Location, appointments.Date, 
                                appointments.Time, appointments.Status, companies.CompanyName, appointments.Modified, appointments.ChangedBy
                              FROM appointments,companies WHERE appointments.ApplicantId=$uid AND companies.UserId=appointments.CompanyCode
                              ORDER BY appointments.Date DESC LIMIT $offset, $no_of_records_per_page ";
                $query = mysqli_query($connection,$SelectSql);

              ?>
              <br><br>

              <div class="col-sm-12">
              <div class="panel panel-primary">
                <div class="panel-heading h4"></div>
                <div class="panel-body" style="color:black">
                  <table id="table" class="table "> 
                    <thead> 

                    </thead> 
                    <tbody> 
                      <tr> 
                        <th>Id</th> 
                        <th>Company</th> 
                        <th>Description</th> 
                        <th>Location</th> 
                        <th>Date</th> 
                        <th>Time</th> 
                        <th>Status</th> 
                        <th>Action</th>
                      </tr> 
                      <?php 
                        while($row = mysqli_fetch_assoc($query)){
                      ?>
                      <tr style="color:<?php echo (substr($row['Date'],0,10)<date("Y-m-d") && $row['Status']=="New")?'red':'' ?>"> 
                        <td><?php echo $row['AppointmentId']; ?></td>
                        <td><?php echo $row['CompanyName']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Location']; ?></td>
                        <td><?php echo $row['Date']; ?></td> 
                        <td><?php echo $row['Time']; ?></td> 
                        <td><?php echo $row['Status']; ?></td> 
                        <td>
                          <a href="ViewAppointment.php?aid=<?php echo $row['AppointmentId']; ?>"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span></a>  
                          
                          <a href="ConfirmAppointment.php?aid=<?php echo $row['AppointmentId'].'&o=a'; ?>" onclick="return ConfirmAccept(<?php echo $row['AppointmentId']; ?>);"><span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" style="display:<?php echo ($row['Status']=='New' && $row['Date']>date("Y-m-d"))?'inline':'none' ?>"></span></a>

                          <a href="ConfirmAppointment.php?aid=<?php echo $row['AppointmentId'].'&o=d'; ?>" onclick="return ConfirmDecline(<?php echo $row['AppointmentId']; ?>);"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true" style="display:<?php echo ($row['Status']=='New' && $row['Date']>date("Y-m-d"))?'inline':'none' ?>"></span></a>

                          <a href="ConfirmAppointment.php?aid=<?php echo $row['AppointmentId'].'&o=u'; ?>"><span class="glyphicon glyphicon-repeat" aria-hidden="true" style="display:<?php echo (substr($row['Modified'],0,10)==date("Y-m-d") AND  $row['ChangedBy']==$uid)?'inline':'none' ?>">
                          </span></a>
                          
                        </td>              
                      </tr> 
                      <?php } ?>
                    </tbody> 
                  </table>

                </div>
              </div>
              <div class="col-lg-12  col-md-12 white-box">
                <ul class="pagination">
                  <li><a href="?pageno=1">First</a></li>
                  <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">                  
                    <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                  </li>
                  <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                    <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                  </li>
                  <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>"><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                </ul>
              </div>        
              </div>

            </div>
            <div id="menu2" class="tab-pane fade">
              <h3>Menu 2</h3>
              <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam.</p>
            </div>
            <div id="menu3" class="tab-pane fade">
              <h3>Menu 3</h3>
              <p>Eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
            </div>
          </div>
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
  $(function() {
      $('a[data-toggle="tab"]').on('click', function(e) {
          window.localStorage.setItem('activeTab', $(e.target).attr('href'));
      });
      var activeTab = window.localStorage.getItem('activeTab');
      if (activeTab) {
          $('#myTab a[href="' + activeTab + '"]').tab('show');
          //window.localStorage.removeItem("activeTab");
      }
  });
  </script>

</body>

</html>
