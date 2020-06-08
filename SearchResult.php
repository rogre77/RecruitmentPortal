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

  //$actual_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }
  $no_of_records_per_page = 20;
  //$no_of_records_per_page = intval($_GET['out']);
  $offset = ($pageno-1) * $no_of_records_per_page;

  require_once('dbconnect.php');

  if(isset($_POST['add'])!=""){
    
    $ApplicantId = $_POST['ApplId'];
    $FirstName = $_POST['FName'];
    $LastName = $_POST['LName'];
    $Suburbs = $_POST['Suburbs'];
    $DateShortlisted = date('Y-m-d');
    $TotalPoints = $_POST['TotalPoints'];
    $LastLogin = $_POST['LastLogin'];
    $EmailSent = "";
    $AppointmentDate = "";
    $AppointmentTime = "";
    $StatusWithUs = "";
    $Remarks = "";
  
    $InsertSQL = "INSERT INTO shortlist (CompanyCode, ApplicantId, FirstName, LastName, DateShortlisted, TotalPoints, EmailSent, 
                  AppointmentDate, AppointmentTime, StatusWithUs, Remarks) 
                  VALUES ('$CompanyCode', '$ApplicantId', '$FirstName', '$LastName', '$DateShortlisted', '$TotalPoints', '$EmailSent', '$AppointmentDate', '$AppointmentTime', '$StatusWithUs', '$Remarks')";
      $result = mysqli_query($connection, $InsertSQL);   
      
      if ($result) {
        //Delete record from searchresults 
        $DeleteSQL = "DELETE FROM searchresults WHERE CompanyCode='$CompanyCode' AND ApplicantId='$ApplicantId'";
        $result = mysqli_query($connection, $DeleteSQL);  
        if (!$result) {
          echo "Error description: " . mysqli_error($connection);
          exit;
        }
      } else {
        echo "Error description: " . mysqli_error($connection);
        exit;
      }    
  }

  $SelectSql = "SELECT * FROM searchresults WHERE CompanyCode='$CompanyCode'";
  $query = mysqli_query($connection, $SelectSql);

	if($query){
        $total_rows=mysqli_num_rows($query);
	}else{
        $fmsg = "Error description: " . mysqli_error($connection);
	}

  $total_pages = ceil($total_rows / $no_of_records_per_page);

  $SelectSql = "SELECT * FROM searchresults 
                WHERE CompanyCode='$CompanyCode' 
                ORDER BY TotalPoints DESC
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

  <title>Search Results</title>

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
            table tr:not(:first-child){
                cursor: pointer;transition: all .25s ease-in-out;
            }
            table tr:not(:first-child):hover{background-color: #ddd;}
          
            .table tbody tr.highlight td {
              background-color: #ddd;
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
      <h1 id="IdsrchResultHdr" align="center" style="color:#FFF"><strong>Search Results</strong></h1>
      <div class="col-sm-6">
      <div class="panel panel-primary">
        <div class="panel-heading">Top Candidates</div>
        <div class="panel-body" style="color:black">
          <table id="table" class="table "> 
            <thead> 

            </thead> 
            <tbody> 
              <tr> 
                <th>Id</th> 
                <th>Name</th> 
                <th>Suburbs</th> 
                <th>Visa Type</th> 
                <th>Work Availability</th> 
              </tr> 
              <?php 
                while($row = mysqli_fetch_assoc($query)){
              ?>
              <tr> 
<!--                <th scope="row"><?php echo $row['FirstName']." ".$row['LastName']; ?></th> -->
                <td><?php echo $row['ApplicantId']; ?></td> 
                <td><?php echo $row['FirstName']." ".$row['LastName']; ?></td>
                <td><?php echo $row['Suburbs']; ?></td>
                <td><?php echo $row['ResidencyStatus']; ?></td>
                <td><?php echo $row['WorkAvailability']; ?></td> 
              </tr> 
              <?php } ?>
            </tbody> 
          </table>
        </div>
      </div>
      <div class="col-lg-6  col-md-6 white-box">
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
      <div class="col-lg-6  col-md-6 white-box">
        <br>
        <form id="SearchResultForm" action="#" method="post" enctype="multipart/form-data" autocomplete="off" >
          <button type="submit" name="add" value="add" class="btn btn-default <?php if($total_rows < 1){ echo 'disabled'; } ?>" style="float: right" >Add to Shortlist</button>   
          <button type="button" name="back" value="back" class="btn btn-default" style="float: right" onclick="location.href='SearchCandidate.php';">Back</button>   
        </form>
      </div>
      </div>
      <div class="col-sm-6">
      <div class="panel panel-primary">
        <div class="panel-heading">Selected Candidate Details</div>
        <div id="ApplicantDetails" class="panel-body" style="color:black">
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#table tbody tr:eq(1)').addClass('highlight').siblings().removeClass('highlight');  
      var cell = $('#table tbody tr:eq(1) td:eq(0)').text();                       
      ShowDetail(cell);
    });
    
    $('#table').on('click', 'tbody tr', function(event) {
      $(this).addClass('highlight').siblings().removeClass('highlight');
    });  
  </script>
  <script>
    var table = document.getElementById('table');
                
    for(var i = 1; i < table.rows.length; i++)
    {
       table.rows[i].onclick = function()
      {
        //rIndex = this.rowIndex;
        //document.getElementById("ApplicantId").innerHTML = this.cells[0].innerHTML;
        ShowDetail(this.cells[0].innerHTML);
      }
    }

  function ShowDetail(str) {
      if (str == "") {
          document.getElementById("ApplicantDetails").innerHTML = "";
          return;
      } else { 
          if (window.XMLHttpRequest) {
              // code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp = new XMLHttpRequest();
          } else {
              // code for IE6, IE5
              xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
          }
          xmlhttp.onreadystatechange = function() {
              if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("ApplicantDetails").innerHTML = this.responseText;
              }
          };
          xmlhttp.open("GET","GetUserDetail.php?uid="+str,true);
          xmlhttp.send();
      }
  }    
  </script>
  
</body>

</html>
