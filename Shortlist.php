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
  //echo "Company:".$CompanyCode;

  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }
  $no_of_records_per_page = 20;
  $offset = ($pageno-1) * $no_of_records_per_page;

  require_once('dbconnect.php');

  $SelectSql = "SELECT * FROM shortlist WHERE CompanyCode = '$CompanyCode'";
  $query = mysqli_query($connection, $SelectSql);

	if($query){
        $total_rows=mysqli_num_rows($query);
	}else{
        $fmsg = "Error description: " . mysqli_error($connection);
	}

  $total_pages = ceil($total_rows / $no_of_records_per_page);

  /*$SelectSql = "SELECT shortlist.ApplicantId, shortlist.FirstName, shortlist.LastName, applicants.Suburbs, appointments.Date, 
                       appointments.Time, shortlist.StatusWithUs, shortlist.Remarks, users.LastLogin, applicants.Status 
                 FROM shortlist,users,applicants,appointments 
                WHERE shortlist.ApplicantId=users.UserId 
                  AND shortlist.ApplicantId=applicants.UserId
                  AND shortlist.ApplicantId=appointments.ApplicantId
                ORDER by shortlist.TotalPoints DESC  
                LIMIT $offset, $no_of_records_per_page";
  */  
  $SelectSql = "SELECT shortlist.ApplicantId, users.FirstName, users.LastName, applicants.Suburbs, appointments.Date, 
                       appointments.Time, shortlist.StatusWithUs, shortlist.Remarks, users.LastLogin, applicants.Status 
                  FROM shortlist
                 INNER JOIN users on shortlist.ApplicantId=users.UserId
                 INNER JOIN applicants on shortlist.ApplicantId=applicants.UserId
		              LEFT JOIN appointments on appointments.ApplicantId=shortlist.ApplicantId
                   AND appointments.CompanyCode = '$CompanyCode'
                   AND (appointments.Status='New' OR appointments.Status='Accepted')
                 WHERE shortlist.CompanyCode = '$CompanyCode'
                 ORDER by shortlist.TotalPoints DESC
                LIMIT $offset, $no_of_records_per_page";

  $query = mysqli_query($connection,$SelectSql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Candidates Shortlisting</title>

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
      <br>
      <h1 id="IdsrchResultHdr" align="center" style="color:#FFF"><strong>Candidates' Shortlist</strong></h1>

      <div class="col-sm-12">
        <div class="panel panel-primary">
          <div class="panel-heading">Shortlisted Candidates</div>
          <div class="panel-body" style="color:black">
            <table id="table" class="table "> 
              <thead> 

              </thead> 
              <tbody> 
                <tr> 
                  <th>Id</th> 
                  <th>Name</th> 
                  <th>Suburbs</th> 
                  <th>Last Login</th> 
                  <th>Appointment</th> 
                  <th>Appl Status</th> 
                  <th>StatusWithUs</th> 
                  <th>Remarks</th> 
                  <th>Action</th>
                </tr> 
                <?php 
                  while($row = mysqli_fetch_assoc($query)){
                ?>
                <tr> 
                  <td><?php echo $row['ApplicantId']; ?></td>
                  <td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>
                  <td><?php echo $row['Suburbs']; ?></td>
                  <td><?php echo $row['LastLogin']; ?></td>
                  <td><?php echo $row['Date']." ".$row['Time']; ?></td>
                  <td><?php echo $row['Status']; ?></td> 
                  <td><?php echo $row['StatusWithUs']; ?></td> 
                  <td><?php echo $row['Remarks']; ?></td> 
                  <td>
                    <a href="ShortlistDetail.php?uid=<?php echo $row['ApplicantId']; ?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>

                    <a href="delete.php?sid=<?php echo $row['ApplicantId'].'&comp='.$CompanyCode; ?>" onclick="return ConfirmDelete(<?php echo $row['ApplicantId']; ?>);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

                  </td>              
                </tr> 
                <?php } ?>
              </tbody> 
            </table>

            <div class="col-lg-12  col-md-12 white-box">
              <ul class="pagination">
                <li><a href="?pageno=1">First</a></li>
                <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">                  
                  <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                </li>
                <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                  <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                </li >
                <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>"><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
              </ul>
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

</body>

</html>
