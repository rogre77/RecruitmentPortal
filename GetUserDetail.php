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

  require_once('dbconnect.php');

  $uid = intval($_GET['uid']);

  $SelectSql="SELECT * FROM searchresults WHERE ApplicantId=$uid ORDER BY TotalPoints DESC";
  $result = mysqli_query($connection,$SelectSql);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
  } else {
    echo "Error description: " . mysqli_error($connection);
    exit;
  }
  
  $SelectSql = "SELECT * FROM workexperience WHERE UserId=$uid";
  $result1 = mysqli_query($connection, $SelectSql);

  $SelectSql = "SELECT * FROM employeeskills WHERE UserId=$uid";
  $result2 = mysqli_query($connection, $SelectSql);
  //mysqli_close($connection);
?>

<!DOCTYPE html>
  <html lang="en">
  <head>
    <style>
      #withborder 
      {
        border: 1px solid blue;
        margin-left: 2px;
        margin-right: 2px;
      } 
    </style>
  </head>

  <body>
    <div id="withborder" class="row " >
      <input type="hidden" name="ApplId" form="SearchResultForm" value="<?php echo $uid; ?>">
      <input type="hidden" name="FName" form="SearchResultForm" value="<?php echo $row['FirstName']; ?>">
      <input type="hidden" name="LName" form="SearchResultForm" value="<?php echo $row['LastName']; ?>">
      <input type="hidden" name="Suburbs" form="SearchResultForm" value="<?php echo $row['Suburbs']; ?>">
      <input type="hidden" name="LastLogin" form="SearchResultForm" value="<?php echo $row['LastLogin']; ?>">
      <input type="hidden" name="TotalPoints" form="SearchResultForm" value="<?php echo $row['TotalPoints']; ?>">
      <legend style="color:blue">Educational Information:</legend>
      <?php if ($row['Degree'] != "") {
      ?>
        <div class="form-group col-sm-9">
          <label class="label label-primary">Degree</label><br>
          <?php echo $row['Degree']; ?>
        </div>
        <div class="form-group col-sm-3">
          <label class="label label-primary">Year Graduated</label><br>
          <?php echo $row['DegreeGraduated']; ?>
        </div>   
      <?php
        }
      ?>

      <?php if ($row['PostGraduate'] != "") {
      ?>
        <div class="form-group col-sm-9">
          <label class="label label-primary" >Post Graduate</label><br>
          <?php echo $row['PostGraduate']; ?>
        </div>
        <div class="form-group col-sm-3">
          <label class="label label-primary">Year Graduated</label><br>
          <?php echo $row['PGGraduated']; ?>
        </div>   
      <?php
        }
      ?>    

      <?php if ($row['Masters'] != "") {
      ?>
        <div class="form-group col-sm-9">
          <label class="label label-primary" >Masters</label><br>
          <?php echo $row['Masters']; ?>
        </div>
        <div class="form-group col-sm-3">
          <label class="label label-primary">Year Graduated</label><br>
          <?php echo $row['MastersGraduated']; ?>
        </div>   
      <?php
        }
      ?>    

      <?php if ($row['Doctorate'] != "") {
      ?>
        <div class="form-group col-sm-9">
          <label class="label label-primary">Doctorate</label><br>
          <?php echo $row['Doctorate']; ?>
        </div>
        <div class="form-group col-sm-3">
          <label class="label label-primary">Year Graduated</label><br>
          <?php echo $row['DoctorateGraduated']; ?>
        </div>   
      <?php
        }
      ?>    
    </div>
    <br>
    
    <div id="withborder" class="row " >
      <legend style="color:blue">Visa Information:</legend>
      <div class="form-group col-sm-9">
        <label class="label label-primary">Residency Status</label> <br>
        <?php echo $row['ResidencyStatus']; ?>
      </div>
      <div class="form-group col-sm-3">
        <label class="label label-primary">Work Availability</label><br>
        <?php echo $row['WorkAvailability']; ?>
      </div>           
    </div>
    <br>
    
    <div id="withborder" class="row " >
      <legend style="color:blue">Other Information:</legend>
      <div class="form-group col-sm-12">
        <label class="label label-primary">Interests</label> <br>
        <?php echo $row['Interests']; ?>
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
              <?php echo $rowe['CompanyCityCountry']; ?>
            </div>
            <div class="form-group col-sm-3">
              <label class="label label-primary">Duration (# of years)</label><br>
              <?php echo $rowe['Duration']; ?>
            </div> 
            <div class="form-group col-sm-12">
              <label class="label label-primary">Title</label><br>
              <?php echo $rowe['Title']; ?>
            </div>
            <div class="form-group col-sm-12">
              <label class="label label-primary">Role</label><br>
              <?php echo $rowe['Role']; ?>
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
          <?php echo $rows['SkillName']; ?>
        </div>
        <div class="col-sm-6 form-group">
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
  </body>

</html>
