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
  <title>Applicant Registration</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-theme.css" rel="stylesheet">

  <!-- siimple style -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link type="text/css" rel="stylesheet" href="project.css"/>
  <script>
    var ct = 0;
    var jid = 1;

    //***************************************************************************************
    //function to add new set of elements (skills)
    //***************************************************************************************
    function newskill1()
    {
      var div1 = document.createElement('div');
      div1.id = ct;
      var boot1 = '<div class="col-sm-6 form-group">';
      var boot2 = '<div class="col-sm-5 form-group">';
      var boot3 = '<div class="col-sm-1 form-group">';
      var boot4 = '</div>';
      // link to delete extended form elements
      var delLink = '<a href="javascript:delIt('+ ct +')">Del</a>';
      var rad = '<input type="radio" name="Lvl['+ct+'][0]" value="1" id="IDLvl['+ct+'][0]" checked> Beginner ' + 
                '<input type="radio" name="Lvl['+ct+'][0]" value="2" id="IDLvl['+ct+'][0]" > Intermediate ' +
                '<input type="radio" name="Lvl['+ct+'][0]" value="3" id="IDLvl['+ct+'][0]" > Advance';
      skill = document.getElementById('IdSkillTbl').value;
      var hdn = '<input type="hidden" name="Skill[]" value="' +skill+ '">';
      document.getElementById('Skill').innerHTML = skill;      
      div1.innerHTML =  boot1 + hdn + skill + boot4 + boot2 + rad + boot4 + boot3 + delLink + boot4;
      document.getElementById('SkillsObj').appendChild(div1);
      ct++;
    }

    //***************************************************************************************
    //function to delete the newly added set of elements (skills)
    //***************************************************************************************
    function delIt1(eleId)
    {
      d = document;
      var ele = d.getElementById(eleId);
      var parentEle = d.getElementById('SkillsObj');
      parentEle.removeChild(ele);
    }
    
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
    <h1 id="IdAppReg" align="center" style="color:#FFF"><strong>Applicant Registration</strong></h1>
    <div class="col-sm-2"></div>    
    <div class="panel panel-primary col-sm-8">
      <div class="panel-heading">Applicant Profile Creation</div>
      <div class="panel-body" style="color:black">
        
      <form id="RegForm" action="adduser.php" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
         <fieldset>
          <div id="PersonalInfo">
          <legend>Personal Information:</legend>
           <div class="form-group col-sm-6">
            <label id="lblFirstName" class="label label-primary">First Name</label>
            <input type="text" class="form-control" id="IdFirstName" name="FName" placeholder="First name" maxlength="25" required autofocus>
          </div>
           <div class="form-group col-sm-6">
            <label id="lblLastName" class="label label-primary">Last Name</label>
            <input type="text" class="form-control" id="IdLastName" name="LName" placeholder="Last name" maxlength="25" required>
          </div>           
          <div class="form-group col-sm-12">
            <label id="lblEmail" class="label label-primary">Email</label>
            <input type="email" class="form-control" id="IdEmail" name="Email" maxlength="50" aria-describedby="emailHelp" placeholder="Enter email address" onBlur="CheckMail(this.value)" required>
            <small id="emailHelp" class="form-text text-muted" style="color:red"></small>
          </div>           
          <div class="form-group col-md-6">
            <label id="lblPassword1" class="label label-primary">Password</label>
            <input type="password" class="form-control" id="IdPassword1" name="Password1" maxlength="15" placeholder="Password" data-minlength="6" required>
            <small id="psw1Help" class="form-text text-muted">Passwords should not be less than 8</small>
          </div>
          <div class="form-group col-md-6">
            <label id="lblPassword2" class="label label-primary">Verify Password</label>
            <input type="password" class="form-control" id="IdPassword2" name="Password2" maxlength="15" placeholder="Confirm" data-match="#Password1" data-match-error="Whoops, these don't match" required>
            <small id="psw2Help" class="form-text text-muted">Passwords should not be less than 8</small>
          </div>     
          <div class="form-group col-md-6">
            <label id="lblPhone" class="label label-primary">Phone</label>
            <input type="text" class="form-control" id="IdPhone" name="Phone" maxlength="15" placeholder="Mobile Number" required>
          </div>           
          <div class="form-group col-md-6">
            <label id="lblSuburbs" class="label label-primary">City/Suburbs</label>
            <select class="form-control" id="IdSuburbs" name="Suburbs">
              <option selected>Choose...</option>
              <?php
                while ($row = mysqli_fetch_assoc($query1)) {
              ?>    
                <option value="<?php echo $row['SuburbName']; ?>" ><?php echo $row['SuburbName']; ?></option>
              <?php
                }
              ?>
            </select>
          </div>
           </div>
          <br><br>
          .
          <br>
          <div id="EducationalInfo">
          <legend>Educational Information:</legend>
          <div class="form-group col-sm-9">
            <label id="lblDegree" class="label label-primary" for="IdDegreeDesc">Degree</label>
            <input type="text" class="form-control" id="IdDegreeDesc" name="Degree" maxlength="50" placeholder="e.g. Bachelor of Science in Computer Engineering">
          </div>
          <div class="form-group col-sm-3">
            <label id="lblDegreeGYear" class="label label-primary" for="IdDegreeGYear">Year Graduated</label>
            <input type="text" class="form-control" id="IdDegreeGYear" name="DegreeGYear" maxlength="04" placeholder="e.g 2015">
          </div>   
          <div class="form-group col-sm-9">
            <label id="lblPostGraduate" class="label label-primary" for="IdPGradDesc">Post-Graduate</label>
            <input type="text" class="form-control" id="IdPGradDesc" name="PGradDesc" maxlength="50" placeholder="e.g. Diploma in Information Technology">
          </div>
          <div class="form-group col-sm-3">
            <label id="lblPostGraduateGYear" class="label label-primary" for="IdPGradGYear">Year Graduated</label>
            <input type="text" class="form-control" id="IdPGradGYear" name="PGradGYear" maxlength="04" placeholder="e.g 2015">
          </div>  
          <div class="form-group col-sm-9">
            <label id="lblMasters" class="label label-primary" for="IdMastersDesc">Masters</label>
            <input type="text" class="form-control" id="IdMastersDesc" name="MastersDesc" maxlength="50" placeholder="e.g. Master of Science">
          </div>
          <div class="form-group col-sm-3">
            <label id="lblMastersGYear" class="label label-primary" for="IdMastersGYear">Year Graduated</label>
            <input type="text" class="form-control" id="IdMastersGYear" name="MastersGYear" maxlength="04" placeholder="e.g 2015">
          </div>  
          <div class="form-group col-sm-9">
            <label id="lblDoctorate" class="label label-primary" for="IdDoctorateDesc">Doctorate</label>
            <input type="text" class="form-control" id="IdDoctorateDesc" name="DoctorateDesc" maxlength="50" placeholder="e.g. Doctor of Philosophy">
          </div>
          <div class="form-group col-sm-3">
            <label id="lblDoctorateGYear" class="label label-primary" for="IdDoctorateGYear">Year Graduated</label>
            <input type="text" class="form-control" id="IdDoctorateGYear" name="DoctorateGYear" maxlength="04" placeholder="e.g 2015">
          </div>  
           </div>
          <br>
          .
          <br>
          <div id="VisaInfo">
          <legend>Visa Information:</legend>
          <div class="form-group col-sm-8">
            <label class="label label-primary" for="IdVisa"> Residency Status</label> <br>
            <input type="radio" name="visa" value="Holiday Visa" id="IdVisa" checked> Holiday Visa 
            <input type="radio" name="visa" value="Student Visa" id="IdVisa"> Student Visa
            <input type="radio" name="visa" value="Work Visa" id="IdVisa"> Work Visa
            <input type="radio" name="visa" value="NZ Permanent Resident" id="IdVisa"> NZ Permanent Resident
          </div>
          <div class="form-group col-sm-4">
            <label class="label label-primary" for="IdHours"> Work Availability</label> <br>
            <input type="radio" name="hours" value="20 hours" id="IdHours" checked> 20 hours
            <input type="radio" name="hours" value="Full Time" id="IdHours"> Full Time
          </div>    
          </div>
          <br>
          .
          <div id="OtherInfo">
          <legend>Other Information:</legend>
          <div class="form-group col-sm-12">
            <label class="label label-primary">Interests</label>
            <input type="text" class="form-control" id="IdInterests" name="Interests" maxlength="100" placeholder="e.g. Basketball, Shopping, Online Games">
          </div>    
          </div>
          <br>
          <div id="IDWorkInfo">
            <legend>Work Experience:</legend>
            <div class="form-group col-sm-12"> 
            <h4>Job # 1</h4>
            </div>
            <div class="form-group col-sm-9">
              <label id="lblJob1" class="label label-primary">Company,City,Country</label>
              <input type="text" class="form-control" id="IdJob1" name="Job1" maxlength="100" placeholder="e.g. The Warehouse, Auckland, New Zealand">
            </div>
            <div class="form-group col-sm-3">
              <label id="lblDuration1" class="label label-primary">Duration (# of years)</label>
              <input type="text" class="form-control" id="IdDuration1" name="Duration1" maxlength="05" placeholder="e.g. 3.5">
            </div> 
            <div class="form-group col-sm-12">
              <label id="lblTitle1" class="label label-primary">Title</label>
              <input type="text" class="form-control" id="IdTitle1" name="Title1" maxlength="50" placeholder="e.g. Web Developer">
            </div>
            <div class="form-group col-sm-12">
              <label id="lblRole1" class="label label-primary">Role</label>
              <textarea class="form-control" id="IdRole1" name="Role1" maxlength="200" rows="3" placeholder="Designed and developed websites. Technical team leader."></textarea>
            </div>   
            
          </div>     
          <br>
          <button type="button" class="btn btn-success" onclick="newjob()" style="float: right">Add Work Experience</button> 
          <br>
          <div class="row"></div>
          <div id="IDWorkTmpl"  style="display:none">

            <div class="form-group col-sm-9">
              <label class="label label-primary" for="IdJobx[]">Company,City,Country</label>
              <input type="text" class="form-control" id="IdJobx[]" name="Jobx[]" maxlength="100" placeholder="e.g. The Warehouse, Auckland, New Zealand">
            </div>
            <div class="form-group col-sm-3">
              <label class="label label-primary" for="IdDurationx[]">Duration (# of years)</label>
              <input type="text" class="form-control" id="IdDurationx[]" name="Durationx[]" maxlength="05" placeholder="e.g. 3.5">
            </div> 
            <div class="form-group col-sm-12">
              <label class="label label-primary" for="IdTitlex[]">Title</label>
              <input type="text" class="form-control" id="IdTitlex[]" name="Titlex[]" maxlength="50" placeholder="e.g. Web Developer">
            </div>
            <div class="form-group col-sm-12">
              <label class="label label-primary" for="IdRolex[]">Role</label>
              <textarea class="form-control" id="IdRolex[]" name="Rolex[]" maxlength="200" rows="3" placeholder="Designed and developed websites. Technical team leader."></textarea>
            </div>   
            <br>
          </div>       
          <br>
           
          <div id="IDSkillsSet">
          <legend>Skills Set:</legend>
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
            .
            <br>
            <button type="button" class="btn btn-success" onclick="newskill()">Add</button>             
          </div>          
          <div class="form-group col-sm-6"> 
            <h4>------------Self Assessment-------------</h4>
          </div>
           <div class="form-group col-sm-12">
           </div>
          <div id="SkillsObj">
            .
          </div>
          <br>
          <div id="skilltpl" style="display:none">
            <div id="Skill">xx</div>
          </div>
          </div>
          </fieldset>
        
          <p id="Skillcnt1" hidden>100</p>
          <p id="skillarray" hidden></p>
          <p id="error" style="color:red"></p>          
          <hr class="style1">
          
          <div class="form-group col-sm-9">
          </div>
          <div class="form-group col-sm-3">
            <button type="button" class="btn btn-success" onclick="location.href='home.php';">Cancel</button>
            <button type="submit" class="btn btn-success" onclick="return ValidateApplicant();" >Submit</button>   
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

  <!--
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  -->
  <script src="/JQuery/jquery-3.4.1.min.js"></script>
              <script src="assets/js/bootstrap.min.js"></script>
  <script src="recruit.js"></script>    

</body>

</html>
