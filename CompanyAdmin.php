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
  if($_SESSION['Type'] != "Administrator" ){
    echo "<script>alert('Sorry you do not have the privilege to access this page!');</script>";
    echo "<script>window.history.back();</script>";
    exit;
  }

  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }
  $no_of_records_per_page = 20;
  $offset = ($pageno-1) * $no_of_records_per_page;

  require_once('dbconnect.php');
  $SelectSql = "SELECT * 
                FROM users
                WHERE Type='Employer'";
  $query = mysqli_query($connection, $SelectSql);

	if($query){
    $total_rows=mysqli_num_rows($query);
	}else{
    $error = "Error description: " . mysqli_error($connection);
	}

  $total_pages = ceil($total_rows / $no_of_records_per_page);

  $SelectSql = "SELECT users.UserId, users.Status, users.LastLogin, companies.CompanyName, companies.Website 
                FROM users,companies 
                WHERE Type='Employer' AND users.UserId=companies.UserId 
                LIMIT $offset, $no_of_records_per_page";
  $query = mysqli_query($connection,$SelectSql);

  if(!$query){
    $error = "Error description: " . mysqli_error($connection);
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
  <title>Companies Administration</title>

  <!-- Bootstrap -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/css/bootstrap-theme.css" rel="stylesheet">

  <!-- siimple style -->
  <link href="assets/css/style.css" rel="stylesheet">
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
          <li><a href="CompanyAdmin.php">Companies</a></li>
          <li><a href="UserAdmin.php">Applicants</a></li>
          <li><a href="SuburbsAdmin.php">Suburbs</a></li>
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
      <br>
      <h1 id="IdCompAdminHdr" align="center" style="color:#FFF"><strong>Companies Administration</strong></h1>
      <div class="panel panel-primary">
        <div class="panel-heading">List of Companies</div>
        <div class="panel-body" style="color:black">
          <table class="table "> 
            <thead> 
              <tr> 
                <th>Id</th> 
                <th>Company Name</th> 
                <th>Website</th> 
                <th>Status</th>
                <th>Last Login</th>
                <th>Action</th>
              </tr> 
            </thead> 
            <tbody> 

              <?php 
                while($row = mysqli_fetch_assoc($query)){
              ?>
              <tr> 
                <th scope="row"><?php echo $row['UserId']; ?></th> 
                <td><?php echo $row['CompanyName']; ?></td>
                <td><?php echo $row['Website']; ?></td>
                <td><?php echo $row['Status']; ?></td> 
                <td><?php echo $row['LastLogin']; ?></td>
                <td>
                  <a href="EditCompany.php?cmp=<?php echo $row['UserId']; ?>"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>

                   <a href="delete.php?cmp=<?php echo $row['UserId']; ?>" onclick="return ConfirmDelete(<?php echo $row['UserId']; ?>);"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>

                  <script language='JavaScript'>
                    function validate(id) {
                      if (confirm('Are you sure you want to delete this ID!?  ' + id)) {
                        return true;
                      } else {
                        return false;
                      }
                    }
                  </script>

                </td>
              </tr> 
              <?php } ?>
            </tbody> 
          </table>
        </div>
      
        <div class="col-lg-3  col-md-6 white-box">
          <ul class="pagination">
            <li><a href="?pageno=1">First</a></li>
            <li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">                  
              <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
            </li>
            <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
              <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
            </li>
            <li><a href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
          </ul>
        </div>        
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
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="assets/js/bootstrap.min.js"></script>
  <script src="recruit.js"></script>    
</body>

</html>
