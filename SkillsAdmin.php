<?php 
  session_start();
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  //Checking for session expiry or not login (user id is empty)
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
  $error = '';

  if(isset($_POST['btnSave'])!=""){
    $SkillName = $_POST['SkillName'];
    
    $InsertSQL = "INSERT INTO skills (CompanyCode, SkillName) VALUES ('$CompanyCode','$SkillName')";
    $result = mysqli_query($connection, $InsertSQL);   
      
    if (!$result) {   
      $error="Error: ". mysqli_error($connection)." Please check your entry. Thank you!";
    }
  }

  if (isset($_GET['pageno'])) {
      $pageno = $_GET['pageno'];
  } else {
    $pageno = 1;
  }
  $no_of_records_per_page = 15;
  $offset = ($pageno-1) * $no_of_records_per_page;

  require_once('dbconnect.php');
  $SelectSql = "SELECT * FROM skills";
  $query = mysqli_query($connection, $SelectSql);

	if($query){
        $total_rows=mysqli_num_rows($query);
	}else{
        $fmsg = "Error description: " . mysqli_error($connection);
	}

  $total_pages = ceil($total_rows / $no_of_records_per_page);

  $SelectSql = "SELECT * FROM skills ORDER BY SkillName LIMIT $offset, $no_of_records_per_page";
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
  <title>Skills Table Administration</title>

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
      <br>
      <h1 id="IdUserAdminHdr" align="center" style="color:#FFF"><strong>Skills Table Administration</strong></h1>
      <div class="col-sm-2"></div>
      <div class="panel panel-primary col-sm-8">
        <div class="panel-heading">List of Skills</div>
        <div class="panel-body" style="color:black">
          <table class="table" style="color:black"> 
            <thead> 
              <tr> 
                <th>Skill Name</th> 
                <th>Action</th>
              </tr> 
            </thead> 
            <tbody> 

              <?php 
                while($row = mysqli_fetch_assoc($query)){
              ?>
              <tr> 
                <td><?php echo $row['SkillName']; ?></td>
                <td>
                  <a href="EditSkill.php?kid='<?php echo $row['SkillName']; ?>'"><span class="glyphicon glyphicon-edit" aria-hidden="true"  style="display:<?php echo ($row['CompanyCode']==$CompanyCode)?'inline':'none' ?>"></span></a>

                   <a href="delete.php?kid=<?php echo $row['SkillName']; ?>" onclick="return ConfirmDelete('<?php echo $row['SkillName']; ?>');"><span class="glyphicon glyphicon-trash" aria-hidden="true" style="display:<?php echo ($row['CompanyCode']==$CompanyCode)?'inline':'none' ?>"></span></a>
                  
                </td>
              </tr> 

              <?php } ?>
            </tbody> 
          </table>
          <hr class="style1">

          <div class="col-sm-5 white-box">
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
          <form id="Form" action="" method="post" enctype="multipart/form-data" data-toggle="validator" autocomplete="off" >
            <div class="col-sm-6">
              <br>
              <input type="text" class="form-control" id="IdSkillName" name="SkillName" value="" autofocus>
            </div> 
            <div class="col-sm-1">
              <br>
              <button type="submit" name="btnSave" class="btn btn-success" onclick="" >Add</button>   
            </div>   
          </form>
        <div class="col-sm-12" id="error" style="color:red"><?php echo $error; ?></div>
          
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
