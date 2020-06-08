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

  $CompanyCode = $_SESSION['UserId'];

  require_once('dbconnect.php');
  $SelectSql = "SELECT SkillName from skills";
  $query1 = mysqli_query($connection, $SelectSql);

  $SelectSql = "SELECT * from searchcriteria WHERE CompanyCode=$CompanyCode";
  $query0 = mysqli_query($connection, $SelectSql);
  $row0 = mysqli_fetch_assoc($query0);
  $MaxOutput = 25;

  if(isset($_POST['save'])!=""){
    //Delete previous results 
    $DeleteSQL = "DELETE FROM searchcriteria WHERE CompanyCode='$CompanyCode'";
    $result = mysqli_query($connection, $DeleteSQL);  
    
    $RecentActivity = $_POST['Activity'];
    $EducKeywords = mysqli_real_escape_string($connection,$_POST['EdKeywords']);
    $WorkKeywords = mysqli_real_escape_string($connection,$_POST['WEKeywords']);
    $OthrKeywords = mysqli_real_escape_string($connection,$_POST['OIKeywords']);
    if(isset($_POST['BegLevel'])) {
      $Beginner = $_POST['BegLevel'];
    } else {
      $Beginner = 0;
    }
    
    if(isset($_POST['IntLevel'])) {
      $Intermediate = $_POST['IntLevel'];
    } else {
      $Intermediate = 0;
    }
    
    if(isset($_POST['AdvLevel'])) {
      $Advance = $_POST['AdvLevel'];
    } else {
      $Advance = 0;
    }    
    if(isset($_POST['Skill'])) {
      $Skills = implode(",",$_POST['Skill']);
    } else {
      $Skills = "";
    }
    
    $InsertSQL = "INSERT INTO searchcriteria (CompanyCode, RecentActivity, MaxOutput, EducKeywords, WorkKeywords, OthrKeywords, Beginner,                       Intermediate, Advance, Skills) 
                  VALUES ('$CompanyCode', '$RecentActivity', '$MaxOutput', '$EducKeywords', '$WorkKeywords', '$OthrKeywords', '$Beginner',         '$Intermediate', '$Advance', '$Skills')";
      $result = mysqli_query($connection, $InsertSQL);   
      
      if ($result) {
        header("location: SearchCandidate.php");
      } else {
        echo "Error description: " . mysqli_error($connection);
        exit;
      }    
  }
     
  if(isset($_POST['search'])!=""){
    //Delete previous results 
    $DeleteSQL = "DELETE FROM searchresults WHERE CompanyCode='$CompanyCode'";
    $result = mysqli_query($connection, $DeleteSQL);    
    
    $MaxOutput = $_POST['MaxOut'];
    $Activity = $_POST['Activity'];
    switch ($Activity) {
      case "1M": 
        $ActiveDate = date("Y-m-d H:i:s", strtotime("-1 months"));
        break;
      case "3M": 
        $ActiveDate = date("Y-m-d H:i:s", strtotime("-3 months"));
        break;
      case "6M": 
        $ActiveDate = date("Y-m-d H:i:s", strtotime("-6 months"));
        break;
      case "1Y": 
        $ActiveDate = date("Y-m-d H:i:s", strtotime("-1 years"));
        break;
      case "2Y": 
        $ActiveDate = date("Y-m-d H:i:s", strtotime("-2 years"));
        break;
      default:
        $ActiveDate = date("Y-m-d H:i:s", strtotime("-10 years"));
        
    }
    
    $SelectSql = "SELECT users.UserId, users.FirstName, users.LastName, users.LastLogin, applicants.Suburbs, applicants.Degree,  
                  applicants.DegreeGraduated, applicants.PostGraduate, applicants.PGGraduated, applicants.Masters, applicants.MastersGraduated, applicants.Doctorate, applicants.DoctorateGraduated, applicants.ResidencyStatus, applicants.WorkAvailability, applicants.Interests 
                  FROM users,applicants WHERE users.Status='Enabled' AND users.Type='Applicant' AND applicants.Status='Available' AND
                  users.LastLogin>='$ActiveDate' AND users.UserId=applicants.UserId";
    $query2 = mysqli_query($connection, $SelectSql);
    
    if (!$query2) {
      echo "Error description: " . mysqli_error($connection);
      exit;
    }

    while ($row = mysqli_fetch_assoc($query2)) {
      $uid = $row['UserId'];
      $SelectSql = "SELECT ApplicantId FROM shortlist WHERE CompanyCode='$CompanyCode' AND ApplicantId='$uid'";
      $result = mysqli_query($connection, $SelectSql);  
      $ShortId = mysqli_fetch_assoc($result);
      if ($ShortId!="") {
        continue;
      }
      
      $EdPoints = 0;
      $OIPoints = 0;
      $TotalWEPoints = 0;
      $SKPoints = 0;
      echo "=========================================== <br/>";
      echo "Applicant ID:".$row['UserId']."<br />";
      $EdKeyArray = explode(',',$_POST['EdKeywords']);
      $OIKeyArray = explode(',',$_POST['OIKeywords']);
      $WEKeyArray = explode(',',$_POST['WEKeywords']);

      // Get points for Degree
      if ($row['Degree'] != "") {
        //echo "Degree:".$row['Degree']."<br />";
        for ($ArrCnt = 0; $ArrCnt < count($EdKeyArray); ++$ArrCnt) {
          //echo "Educational Keywords:".$EdKeyArray[$ArrCnt]."<br />";
          if (stripos($row['Degree'],$EdKeyArray[$ArrCnt]) > -1) {
            $EdPoints++;
          }
        }  
      }

      // Get points for Post Graduate
      if ($row['PostGraduate'] != "") {
        //echo "PostGraduate:".$row['PostGraduate']."<br />";
        for ($ArrCnt = 0; $ArrCnt < count($EdKeyArray); ++$ArrCnt) {
          if (stripos($row['PostGraduate'],$EdKeyArray[$ArrCnt]) > -1) {
            $EdPoints = $EdPoints + 2;
          }

        }  
      }
      // Get points for Masters
      if ($row['Masters'] != "") {
        //echo "Masters:".$row['Masters']."<br />";
        for ($ArrCnt = 0; $ArrCnt < count($EdKeyArray); ++$ArrCnt) {
          if (stripos($row['Masters'],$EdKeyArray[$ArrCnt]) > -1) {
            $EdPoints = $EdPoints + 3;
          }
        }  
      }

      // Get points for Doctorate 
      if ($row['Doctorate'] != "") {
        //echo "Doctorate:".$row['Doctorate']."<br />";
        for ($ArrCnt = 0; $ArrCnt < count($EdKeyArray); ++$ArrCnt) {
          if (stripos($row['Doctorate'],$EdKeyArray[$ArrCnt]) > -1) {
            $EdPoints = $EdPoints + 4;
          }
        }  
      }
      echo "EdPoints:".$EdPoints."<br />";
      
      // Get points for Interests 
      if ($row['Interests'] != "") {
        //echo "Interests:".$row['Interests']."<br />";
        for ($ArrCnt = 0; $ArrCnt < count($OIKeyArray); ++$ArrCnt) {
          if (stripos($row['Interests'],$OIKeyArray[$ArrCnt]) > -1) {
            $OIPoints++;
          }
        }  
      }      
      echo "OIPoints:".$OIPoints."<br />";
      
      // Get points for work experience
      $SelectSql = "SELECT * from workexperience where UserId=$uid";
      $query3 = mysqli_query($connection, $SelectSql);
 
      while ($rowe = mysqli_fetch_assoc($query3)) {
        $WEPoints = 0;
        if ($rowe['Role'] != "") {
          //echo "Role:".$rowe['Role']."<br />";
          for ($ArrCnt = 0; $ArrCnt < count($WEKeyArray); ++$ArrCnt) {
            if (stripos($rowe['Role'],$WEKeyArray[$ArrCnt]) > -1) {
              $WEPoints++;
            }
          }  
        }
        $WEPoints = $WEPoints * $rowe['Duration'];
        //echo "WEPoints:".$WEPoints."<br />";
        $TotalWEPoints = $TotalWEPoints + $WEPoints;
      }
      echo "WEPoints:".$TotalWEPoints."<br/>";

      // Get points for Skills
      if(isset($_POST['Skill'])) {
        $skl = $_POST['Skill'];
        if (isset($_POST['BegLevel'])) {
          $BegLevel = $_POST['BegLevel'];
        } else {
          $BegLevel = 0;
        }
        
        if (isset($_POST['IntLevel'])) {
          $IntLevel = $_POST['IntLevel'];
        } else {
          $IntLevel = 0;
        }

        if (isset($_POST['AdvLevel'])) {
          $AdvLevel = $_POST['AdvLevel'];
        } else {
          $AdvLevel = 0;
        }

        $SelectSql = "SELECT * from employeeskills where UserId=$uid and (ExpertLevel=$BegLevel or 
                      ExpertLevel=$IntLevel or ExpertLevel=$AdvLevel)";
        $query4 = mysqli_query($connection, $SelectSql);

        while ($rows = mysqli_fetch_assoc($query4)) {
          //echo "row:".$rows['SkillName']."<br/>";
          if (in_array($rows['SkillName'],$skl)) {
            //echo "Skill: ".$rows['SkillName']."-".$rows['ExpertLevel']."<br/>";
            $SKPoints = $SKPoints + $rows['ExpertLevel'];
          }        
        }
        echo "Skill Points:".$SKPoints."<br/>";
      }
      
    $ApplicantId = $row['UserId'];
    $EducationalPoints = $EdPoints;
    $OtherPoints = $OIPoints;
    $WorkPoints = $TotalWEPoints;
    $SkillPoints = $SKPoints;
    $TotalPoints = $EdPoints + $OIPoints + $TotalWEPoints + $SKPoints;
    $FirstName = $row['FirstName'];
    $LastName = $row['LastName'];
    $Suburbs = $row['Suburbs'];
    $ResidencyStatus = $row['ResidencyStatus'];
    $WorkAvailability = $row['WorkAvailability'];
    $Degree = $row['Degree'];
    $DegreeGraduated = $row['DegreeGraduated'];
    $PostGraduate = $row['PostGraduate'];
    $PGGraduated = $row['PGGraduated'];
    $Masters = $row['Masters'];
    $MastersGraduated = $row['MastersGraduated'];
    $Doctorate = $row['Doctorate'];
    $DoctorateGraduated = $row['DoctorateGraduated'];
    $Interests = $row['Interests'];
    $LastLogin = $row['LastLogin'];
    echo "Total Points:".$TotalPoints."<br/>";
     
    if($TotalPoints > 0)  {
      $InsertSQL = "INSERT INTO searchresults (CompanyCode, ApplicantId, EducationalPoints, OtherPoints, WorkPoints, SkillPoints, TotalPoints,                   FirstName, LastName, Suburbs, ResidencyStatus, WorkAvailability, Degree, DegreeGraduated, PostGraduate, PGGraduated, 
                      Masters, MastersGraduated, Doctorate, DoctorateGraduated, Interests, LastLogin) 
                    VALUES ('$CompanyCode', '$ApplicantId', '$EducationalPoints', '$OtherPoints', '$WorkPoints', '$SkillPoints', '$TotalPoints',         '$FirstName', '$LastName', '$Suburbs', '$ResidencyStatus', '$WorkAvailability', '$Degree', '$DegreeGraduated',               '$PostGraduate', '$PGGraduated', '$Masters', '$MastersGraduated', '$Doctorate', '$DoctorateGraduated', '$Interests', '$LastLogin')";
      $result = mysqli_query($connection, $InsertSQL);   
      
      if (!$result) {
        echo "Error description: " . mysqli_error($connection);
        exit;
      }
    }
    }
    //header("location: SearchResult.php?out=".$MaxOutput);
    header("location: SearchResult.php");
    //exit;
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

  <title>Search Candidates</title>

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
  
  <div class = "container" id="cont">
    <h1 id="IdAppReg" align="center" style="color:#FFF"><strong>Search Criteria</strong></h1>
    <div class="form-group col-md-2">
    </div>
    <div class="panel panel-primary col-sm-8">
      <div class="panel-heading">Candidate Selection Criteria</div>
      <div class="panel-body" style="color:black">
        
        <form id="SearchForm" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
          <fieldset>
          <div class="form-group  col-md-4">
            <label class="label label-primary" for="IdActivity">Recent activity</label>
            <select class="form-control" id="IdActivity" name="Activity">
              <option value="1M" <?php echo ($row0['RecentActivity']=="1M")?'selected':'' ?>>1 Month ago</option>
              <option value="3M" <?php echo ($row0['RecentActivity']=="3M")?'selected':'' ?>>3 Months ago</option>
              <option value="6M" <?php echo ($row0['RecentActivity']=="6M")?'selected':'' ?>>6 Months ago</option>
              <option value="1Y" <?php echo ($row0['RecentActivity']=="1Y")?'selected':'' ?>>1 year ago</option>
              <option value="2Y" <?php echo ($row0['RecentActivity']=="2Y")?'selected':'' ?>>2 years ago</option>
              <option value="2Y+" <?php echo ($row0['RecentActivity']=="2Y+")?'selected':'' ?>>more than 2 years ago</option>
            </select>
          </div>            
          <div class="form-group col-md-5">
          </div>          
          <div class="form-group col-md-3">
            <!--
            <label class="label label-primary" for="IdMaxOut">Maximum output:</label>
            <input type="text" class="form-control" id="IdMaxOut" name="MaxOut" maxlength= "3" size="3" value="<?php echo ($row0['MaxOutput']=='')?'25':$row0['MaxOutput'] ?>" required>
            -->
          </div> 
            
          <div class="form-group col-md-12">
            <label class="label label-primary" for="IdEdKeywords">Educational Information Keywords: (Keywords separated by commas)</label>
                <input type="text" class="form-control" id="IdEdKeywords" name="EdKeywords" maxlength="200" value="<?php echo $row0['EducKeywords']; ?>" placeholder="e.g. Computer Engineer, Computer Science, Analytics">
          </div> 
          <div class="form-group col-md-12">
            <label class="label label-primary" for="IdWEKeywords">Work Experience Keywords: (Keywords separated by commas)</label>
                <input type="text" class="form-control" id="IdWEKeywords" name="WEKeywords" maxlength="200" value="<?php echo $row0['WorkKeywords']; ?>" placeholder="e.g. system design, graphics design, ux design, ui design, team lead, team player, full stack">
          </div>    
          <div class="form-group col-md-12">
            <label class="label label-primary" for="IdOIKeywords">Other Interests Keywords: (Keywords separated by commas)</label>
                <input type="text" class="form-control" id="IdOIKeywords" name="OIKeywords" maxlength="200" value="<?php echo $row0['OthrKeywords']; ?>" placeholder="e.g. Modern Technology, Video Games, Mind Games, Sports, ">
          </div> 

          <br>
          <div class="form-group col-sm-4"> 
              <label class="label label-primary" for="IdSkills">Having the following skills:</label>
              <select class="form-control" id="IdSkillTbl">
                <option selected>Choose...</option>
                <?php
                  while ($row = mysqli_fetch_assoc($query1)) {
                ?>    
                  <option value="<?php echo $row['SkillName']; ?>" ><?php echo $row['SkillName']; ?></option>
                <?php
                  }
                ?>                
              </select>
          </div>
          <div class="form-group col-sm-2"> 
            <span></span> 
            <br>
            <button type="button" class="btn btn-success" onclick="newSCSkill()">Add</button>             
          </div>          
          <div class="form-group col-sm-6"> 
              <label class="label label-primary" for="">Level of Expertise:</label> <br>
            <label class="checkbox-inline"><input type="checkbox" name="BegLevel" value="1" <?php echo ($row0['Beginner']=='1')?'checked':'' ?>>Beginner</label>
            <label class="checkbox-inline"><input type="checkbox" name="IntLevel" value="2" <?php echo ($row0['Intermediate']=='2')?'checked':'' ?>>Intermediate</label>
            <label class="checkbox-inline"><input type="checkbox" name="AdvLevel" value="3" <?php echo ($row0['Advance']=='3')?'checked':'' ?>>Advance</label>
          </div>
          <div class="row"></div>
            
          <div id="SkillsObj">
            <?php
              if ($row0['Skills'] != "") {
                $SkillArray = explode(',',$row0['Skills']);
                for ($ArrCnt = 0; $ArrCnt < count($SkillArray); ++$ArrCnt) {
            ?>      
                  <div id="<?php echo $ArrCnt; ?>">
                  <input type="hidden" name="Skill[]" value="<?php echo $SkillArray[$ArrCnt]; ?>">
                  <div class="col-sm-6 form-group">
                    <?php echo $SkillArray[$ArrCnt]; ?>
                  </div>
                  <div class="col-sm-6 form-group">
                    <a href="javascript:delIt(<?php echo $ArrCnt; ?>)">Delete</a>
                  </div>  
                  </div>
            <?php
                }  
              }
            
            ?>
          </div>
            
          </fieldset>
          <p id="skillarray" hidden><?php echo $row0['Skills']; ?></p>
          <p id="error" style="color:red"></p>
          <hr class="style1">
          
          <div class="form-group col-sm-7">
          </div>
          <div class="form-group col-sm-5">
            <button type="submit" name="save"   value="save" class="btn btn-success">Save Criteria</button>   
            <button type="submit" name="search" value="search" class="btn btn-success">Search</button>   
            <button type="button" name="cancel" value="cancel" class="btn btn-success" onclick="location.href='home.php';">Cancel</button>
          </div>          
        </form>
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

</body>

</html>
