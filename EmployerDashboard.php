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

  if($_SESSION["Type"] == "Applicant") {
    header("Location: UserDashboard.php"); /* Redirect to applicant home page */ 
  } 
          
  if( $_SESSION["Type"] == "Administrator") {  
    header("Location: home.php"); /* Redirect to admin home page */ 
  } 
  require_once('dbconnect.php');
  $error = "";
  $uid = $_SESSION['UserId'];

  if(isset($_POST['btnSaveProfile'])!=""){
    $CompanyName = mysqli_real_escape_string($connection,$_POST['CompanyName']);
    $Website = mysqli_real_escape_string($connection,$_POST['Website']);
    $Address = mysqli_real_escape_string($connection,$_POST['Address']);

    $FirstName = mysqli_real_escape_string($connection,$_POST['FirstName']);
    $LastName = mysqli_real_escape_string($connection,$_POST['LastName']);
    $Position = mysqli_real_escape_string($connection,$_POST['Position']);
    $Email = $_POST['Email'];
    $Phone = $_POST['Phone'];
    $Password = $_POST['Password1'];
    $CurrentPassword = $_POST['Password3'];
    if ($Password != $CurrentPassword) {
      $Password = md5($_POST['Password1']);
    } 
    $UpdateSQL = "UPDATE companies SET CompanyName='$CompanyName', Website='$Website', Address='$Address' 
                  WHERE UserId='$uid'";
    $result = mysqli_query($connection, $UpdateSQL);   
      
    if ($result) {
      $UpdateSQL = "UPDATE users SET FirstName='$FirstName', LastName='$LastName', Password='$Password', Position='$Position',  Email='$Email', Phone='$Phone', 
                      Password='$Password'
                    WHERE UserId='$uid'";
      $result = mysqli_query($connection, $UpdateSQL);   

      if (!$result) {
        $error="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
      }   
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
  <title>Employer Dashboard</title>

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

  <div class="container">
    <div class="row">
      <div id="sess"></div>
      <h1 id="IdUserAdminHdr" align="center" style="color:#FFF"><strong>Employer Dashboard</strong></h1>
      <div class="panel panel-primary">
        <div class="panel-body" style="color:black">
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a data-toggle="tab" href="#MyProfile">My Profile</a></li>
            <li>               <a data-toggle="tab" href="#MyAppointments">My Appointments</a></li>
          </ul>

          <div class="tab-content">
            <div id="MyProfile" class="tab-pane fade in active" >
              <?php
                $SelectSql = "SELECT users.FirstName, users.LastName, users.Position, users.Email, users.Phone, users.Password, 
                                companies.CompanyName, companies.Website, companies.Address 
                              FROM users,companies WHERE users.UserId=$uid 
                              AND users.UserId=companies.UserId";
                $result = mysqli_query($connection, $SelectSql);

                if ($result) {
                  $row = mysqli_fetch_assoc($result);   
                } else {
                  $error="Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
                }
              ?>
              
              <br><br>
              <div class="col-sm-2">
              </div>
              <div class="panel panel-primary col-sm-8">
                <div class="panel-heading">Employer Profile</div>
                <div class="panel-body" style="color:black">

                <form id="RegForm" action="" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
                   <fieldset>
                    <legend>Company Details:</legend>
                     <div class="form-group col-sm-6">
                        <label id="lblCompanyName" class="label label-primary">Company Name</label><br>
                        <span class="ViewObj"><?php echo $row['CompanyName']; ?></span>
                        <input type="text" class="form-control UpdtObj" id="IdCompanyName" name="CompanyName" placeholder="Company Name" value="<?php echo $row['CompanyName']; ?>" style="display:none" required autofocus>
                      </div>
                      <div class="form-group col-sm-6">
                        <label id="lblWebsite" class="label label-primary">Website</label><br>
                        <span class="ViewObj"><?php echo $row['Website']; ?></span>
                        <input type="text" class="form-control UpdtObj" id="IdWebsite" name="Website" placeholder="Website" value="<?php echo $row['Website']; ?>" style="display:none" required>
                      </div>

                      <div class="form-group col-sm-12">
                        <label id="lblAddress" class="label label-primary">Address</label><br>
                        <span class="ViewObj"><?php echo $row['Address']; ?></span>
                        <input type="text" class="form-control UpdtObj" id="IdAddress" name="Address" placeholder="Address" value="<?php echo $row['Address']; ?>" style="display:none" required>
                      </div>      
                     
                      <legend>Contact Person:</legend>
                      <div class="form-group col-sm-6">
                        <label id="lblFirstName" class="label label-primary">First Name</label><br>
                        <span class="ViewObj"><?php echo $row['FirstName']; ?></span>
                        <input type="text" class="form-control UpdtObj" id="IdFirstName" name="FirstName" placeholder="First Name" value="<?php echo $row['FirstName']; ?>" style="display:none" required>
                      </div>
                     <div class="form-group col-sm-6">
                      <label id="lblLastName" class="label label-primary">Last Name</label><br>
                      <span class="ViewObj"><?php echo $row['LastName']; ?></span>
                      <input type="text" class="form-control UpdtObj" id="IdLastName" name="LastName" placeholder="Last Name" value="<?php echo $row['LastName']; ?>" style="display:none" required>
                    </div>          
                    
                    <div class="form-group col-md-6">
                      <label id="lblPosition" class="label label-primary">Job Title</label><br>
                      <span class="ViewObj"><?php echo $row['Position']; ?></span>
                      <input type="text" class="form-control UpdtObj" id="IdPosition" name="Position" value="<?php echo $row['Position']; ?>" placeholder="Job Title" style="display:none" required>
                    </div>           

                    <div class="form-group col-sm-6">
                      <label id="lblEmail" class="label label-primary">Email</label><br>
                      <span class="ViewObj"><?php echo $row['Email']; ?></span>
                      <input type="email" class="form-control UpdtObj" id="IdEmail" name="Email" aria-describedby="emailHelp" placeholder="Enter email address" value="<?php echo $row['Email']; ?>" style="display:none" required>
                      <small id="emailHelp" class="form-text text-muted"></small>
                    </div>   
                    <div class="form-group col-md-4">
                      <label id="lblPhone" class="label label-primary">Phone</label><br>
                      <span class="ViewObj"><?php echo $row['Phone']; ?></span>
                      <input type="text" class="form-control UpdtObj" id="IdPhone" name="Phone" value="<?php echo $row['Phone']; ?>" placeholder="Mobile Number" style="display:none" required>
                    </div>    
                    <div class="form-group col-md-2 ViewObj"></div>
                    <div class="form-group col-md-4 ViewObj">
                      <label class="label label-primary">Password</label><br>
                      <span><?php echo "****************"; ?></span>
                    </div>
                     
                    <div class="form-group col-md-4 UpdtObj" style="display:none">
                      <label id="lblPsw1" class="label label-primary">Password</label><br>
                      <input type="password" class="form-control" id="IdPassword1" name="Password1" placeholder="Password" value="<?php echo $row['Password']; ?>" data-minlength="6" required>
                    </div>
                    <div class="form-group col-md-4 UpdtObj" style="display:none">
                      <label id="lblPsw2" class="label label-primary">Verify Password</label><br>
                      <input type="password" class="form-control" id="IdPassword2" name="Password2" placeholder="Confirm" value="<?php echo $row['Password']; ?>" data-match="#Password1" data-match-error="Whoops, these don't match" required>
                    </div>         
                    <input type="hidden" class="form-control " id="IdPassword3" name="Password3" value="<?php echo $row['Password']; ?>" >

                    </fieldset>

                    <p id="error" style="color:red"><?php echo $error; ?></p>
                    <hr class="style1">

                    <div class="form-group col-sm-12" align="right">
                      <button type="button" name="btnUpdateProfile" class="btn btn-success ViewObj"         onclick="ShowHideClass('.UpdtObj','.ViewObj')">Update</button>
                      <button type="submit" name="btnCancelProfile" class="btn btn-success UpdtObj" style="display:none" onclick="ShowHideClass('.ViewObj','.UpdtObj')">Cancel</button>
                      <button type="submit" name="btnSaveProfile" class="btn btn-success UpdtObj" style="display:none" onclick="return ValidateEmployer();" >Save</button>   
                    </div>

                  </form>     

                </div>
              </div>
            </div>
            
            <div id="MyAppointments" class="tab-pane fade ">
              <?php
                $CompanyCode = $_SESSION['UserId'];

                if (isset($_GET['pageno'])) {
                    $pageno = $_GET['pageno'];
                } else {
                  $pageno = 1;
                }
                $no_of_records_per_page = 20;
                $offset = ($pageno-1) * $no_of_records_per_page;

                require_once('dbconnect.php');

                $SelectSql = "SELECT * FROM appointments WHERE CompanyCode=$CompanyCode";
                $query = mysqli_query($connection, $SelectSql);

                if($query){
                      $total_rows=mysqli_num_rows($query);
                }else{
                      $error = "Error description: " . mysqli_error($connection);
                }

                $total_pages = ceil($total_rows / $no_of_records_per_page);

                $SelectSql = "SELECT appointments.AppointmentId, appointments.Description, appointments.Location, appointments.Date, 
                                     appointments.Time, appointments.Status, appointments.Modified, appointments.ChangedBy, users.FirstName, users.LastName, users.Email, users.Phone
                                FROM appointments,users WHERE appointments.CompanyCode=$CompanyCode 
                                 AND users.UserId=appointments.ApplicantId
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
                        <th>Applicant</th> 
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
                      <tr style="color:<?php echo (substr($row['Date'],0,10)<date('Y-m-d') && $row['Status']=='New')?'red':'' ?>"> 
                        <td><?php echo $row['AppointmentId']; ?></td>
                        <td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <td><?php echo $row['Location']; ?></td>
                        <td><?php echo $row['Date']; ?></td> 
                        <td><?php echo $row['Time']; ?></td> 
                        <td><?php echo $row['Status']; ?></td> 
                        <td>
                          <a href="UpdateAppointment.php?aid=<?php echo $row['AppointmentId'].'&cp=ed'; ?>">
                            <span class="glyphicon glyphicon-edit" aria-hidden="true" style="display:<?php echo ($row['Status']=='New' && $row['Date']>date("Y-m-d"))?'inline':'none' ?>"></span></a>

                          <a href="delete.php?eid=<?php echo $row['AppointmentId']; ?>" onclick="return ConfirmDelete(<?php echo $row['AppointmentId']; ?>);"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="display:<?php echo ($row['Status']=='New' && $row['Date']>date("Y-m-d"))?'inline':'none' ?>"></span></a>

                          <a href="UpdateAppointment.php?aid=<?php echo $row['AppointmentId'].'&cp=ed&proc=undo'; ?>"><span class="glyphicon glyphicon-repeat" aria-hidden="true" style="display:<?php echo (substr($row['Modified'],0,10)==date("Y-m-d") AND  $row['ChangedBy']==$uid)?'inline':'none' ?>"></span></a>
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

  //***************************************************************************************
  //function to toggle hide/show of classes
  //***************************************************************************************
function ShowHideClass(a,b) {
    var myClasses = document.querySelectorAll(a),
        i = 0,
        l = myClasses.length;

    for (i; i < l; i++) {
      if(myClasses[i].style.display=="none"){
        myClasses[i].style.display = 'inline';
      } else {
        myClasses[i].style.display = 'none';
      }
    }
    HideShowClass(b);
}    
    
function HideShowClass(b) {
    var myClasses = document.querySelectorAll(b),
        i = 0,
        l = myClasses.length;

    for (i; i < l; i++) {
      if(myClasses[i].style.display=="none"){
        myClasses[i].style.display = 'inline';
      } else {
        myClasses[i].style.display = 'none';
      }
    }
}     
    
    //***************************************************************************************
    //function to validate applicant registration form
    //***************************************************************************************
    function ValidatePasswords()
    {
      pwd1 = document.getElementById("IdPassword1").value;
      pwd2 = document.getElementById("IdPassword2").value;
      
      if (pwd1.length < 8) {
        alert("Password Length should not be less than 8!");
        document.getElementById("IdPassword1").focus();
        return false; 
      }
      
      if (pwd2.length < 8) {
        alert("Password Length should not be less than 8!");
        document.getElementById("IdPassword2").focus();
        return false; 
      }
      
      // compare passwords
      if (pwd1 != pwd2) {
        alert("Passwords do not matched!");
        document.getElementById("IdPassword1").focus();
        return false;   
      }
      
      return true;
    }    
  </script>
</body>

</html>
