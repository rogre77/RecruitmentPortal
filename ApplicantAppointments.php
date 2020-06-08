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

  $error = "";
  $uid = intval($_GET['uid']);  
  $CompanyCode = $_SESSION['UserId'];
  //echo "Company:".$CompanyCode;
  $actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']."?uid=";


  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }
  $no_of_records_per_page = 20;
  $offset = ($pageno-1) * $no_of_records_per_page;

  require_once('dbconnect.php');

  $SelectSql = "SELECT * FROM appointments WHERE CompanyCode=$CompanyCode AND ApplicantId=$uid";
  $query = mysqli_query($connection, $SelectSql);

	if($query){
        $total_rows=mysqli_num_rows($query);
	}else{
        $error = "Error description: " . mysqli_error($connection);
	}

  $total_pages = ceil($total_rows / $no_of_records_per_page);

  $SelectSql = "SELECT * FROM appointments 
                 WHERE CompanyCode=$CompanyCode AND ApplicantId=$uid 
                 ORDER BY appointments.Date DESC 
                 LIMIT $offset, $no_of_records_per_page";
  $query = mysqli_query($connection,$SelectSql);

  $SelectSql = "SELECT UserId, FirstName, LastName, Email FROM users WHERE UserId=$uid";
  $query1 = mysqli_query($connection, $SelectSql);

  if ($query1) {
    $row1 = mysqli_fetch_assoc($query1);
  } else {
    $error = "Error: ". mysqli_error($connection)." Please report to admin@gradforce.com. Thank you!";
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

  <title>Applicant's Appointments</title>

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
  
  <div class="container-fluid">
    <div class="row">
      <h1 id="IdsrchResultHdr" align="center" style="color:#FFF"><strong>Applicant's Appointments</strong></h1>
      <div class="col-sm-1">
      </div>
      <div class="col-sm-10">
      <div class="panel panel-primary">
        <div class="panel-heading h4"><?php echo "Applicant's Name: ".$row1['FirstName']." ".$row1['LastName']; ?></div>
        <div class="panel-body" style="color:black">
          <table id="table" class="table "> 
            <thead> 

            </thead> 
            <tbody> 
              <tr> 
                <th>Id</th> 
                <th>Description</th> 
                <th>Location</th> 
                <th>Date Created</th> 
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
                <td><?php echo $row['Description']; ?></td>
                <td><?php echo $row['Location']; ?></td>
                <td><?php echo $row['DateCreated']; ?></td>
                <td><?php echo $row['Date']; ?></td> 
                <td><?php echo $row['Time']; ?></td> 
                <td><?php echo $row['Status']; ?></td> 
                <td>
                  <a href="UpdateAppointment.php?aid=<?php echo $row['AppointmentId'].'&cp=aa'; ?>"><span class="glyphicon glyphicon-edit" aria-hidden="true" style="display:<?php echo ($row['Status']=='New')?'inline':'none' ?>"></span></a>

                  <a href="delete.php?aid=<?php echo $row['AppointmentId'].'&uid='.$uid; ?>" onclick="return ConfirmDelete(<?php echo $row['AppointmentId']; ?>);"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="display:<?php echo ($row['Status']=='New')?'inline':'none' ?>"></span></a>
                          
                  <a href="UpdateAppointment.php?aid=<?php echo $row['AppointmentId'].'&cp=aa&proc=undo'; ?>"><span class="glyphicon glyphicon-repeat" aria-hidden="true" style="display:<?php echo (substr($row['Modified'],0,10)==date("Y-m-d") AND  $row['ChangedBy']==$CompanyCode)?'inline':'none' ?>"></span></a>

                </td>              
              </tr> 
              <?php } ?>
            </tbody> 
          </table>
        </div>
      </div>
      <div class="col-lg-12  col-md-12 white-box">
        <ul class="pagination">
          <li><a href="?uid=<?php echo $uid ?>&pageno=1">First</a></li>
          <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">                  
            <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?uid=<?php echo $uid ?>&pageno=".($pageno - 1); } ?>">Prev</a>
          </li>
          <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
            <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?uid=<?php echo $uid ?>&pageno=".($pageno + 1); } ?>">Next</a>
          </li>
          <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>"><a href="?uid=<?php echo $uid ?>&pageno=<?php echo $total_pages; ?>">Last</a></li>
          <li><a href="SetAppointment.php?uid=<?php echo $uid; ?>">New</a></li>
        </ul>
      </div>        
      </div>
      <div class="col-sm-1">
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
