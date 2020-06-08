<?php 
  session_start();

  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  require_once('dbconnect.php');
  $error = '';

  if(isset($_POST) & !empty($_POST)){
    $Email = $_POST['Email'];
    $FirstName = mysqli_real_escape_string($connection,$_POST['FName']);
    $LastName = mysqli_real_escape_string($connection,$_POST['LName']);
    $Password = md5($_POST['Password1']);
    $Phone = $_POST['Phone'];
    $Status = "Enabled";
    $Created = date("Y-m-d H:i:s");
    
    $Type = "Applicant";
    $Position = "";
    $LastLogin = "";
      
    $Suburbs = $_POST['Suburbs'];  
    $Degree = mysqli_real_escape_string($connection,$_POST['Degree']);  
    $DegreeGraduated = $_POST['DegreeGYear'];  
    $PostGraduate = mysqli_real_escape_string($connection,$_POST['PGradDesc']);  
    $PGGraduated = $_POST['PGradGYear'];  
    $Masters = mysqli_real_escape_string($connection,$_POST['MastersDesc']);  
    $MastersGraduated = $_POST['MastersGYear'];  
    $Doctorate = mysqli_real_escape_string($connection,$_POST['DoctorateDesc']);  
    $DoctorateGraduated = $_POST['DoctorateGYear'];  
    $ResidencyStatus = $_POST['visa'];  
    $WorkAvailability = $_POST['hours'];  
    $Interests = mysqli_real_escape_string($connection,$_POST['Interests']);  
    $HiringCompany = "";  
    
    $SqlCommand = "INSERT INTO users (Email, Type, FirstName, LastName, Password, Phone, Position, LastLogin, Status, Created) 
                  VALUES ('$Email', '$Type', '$FirstName', '$LastName', '$Password', '$Phone', '$Position', '$LastLogin', '$Status', '$Created' )";
    $res = mysqli_query($connection, $SqlCommand);
    if($res){
      $SelectSql = "SELECT UserId from users where Email='$Email'";
      $res = mysqli_query($connection, $SelectSql);
      $row = mysqli_fetch_assoc($res);

      $UserId = $row['UserId'];
      $Status = "Available";  
      $SqlCommand = "INSERT INTO applicants (UserId, Suburbs, Degree, DegreeGraduated, PostGraduate, PGGraduated, Masters, MastersGraduated, Doctorate, DoctorateGraduated, ResidencyStatus, WorkAvailability, Interests, Status, HiringCompany) 
                    VALUES ('$UserId', '$Suburbs', '$Degree', '$DegreeGraduated', '$PostGraduate', '$PGGraduated', '$Masters', '$MastersGraduated', '$Doctorate', '$DoctorateGraduated', '$ResidencyStatus', '$WorkAvailability', '$Interests', '$Status', '$HiringCompany' )";
      $res = mysqli_query($connection, $SqlCommand);
      
      if($res){
        // Insert work experience
        $WorkNo = 0; 
        $CompanyCityCountry = mysqli_real_escape_string($connection,$_POST['Job1']); 
        $Duration = $_POST['Duration1']; 
        $Title = mysqli_real_escape_string($connection,$_POST['Title1']); 
        $Role = mysqli_real_escape_string($connection,$_POST['Role1']); 
   

        if ($_POST['Job1'] != "") {
          $WorkNo++; 
          $SqlCommand = "INSERT INTO workexperience (UserId, WorkNo, CompanyCityCountry, Duration, Title, Role) 
                        VALUES ('$UserId', '$WorkNo', '$CompanyCityCountry', '$Duration', '$Title', '$Role' )";            
          $res = mysqli_query($connection, $SqlCommand);
        }
        
        if(!$res){
          echo "Error description: " . mysqli_error($connection);
          exit;
        }
        
        $cnt = 0;
        while ($_POST['Jobx'][$cnt]) {
          echo "Job:".$cnt." ".$_POST['Jobx'][$cnt];
          $WorkNo = $WorkNo + $cnt+1;
          $CompanyCityCountry = mysqli_real_escape_string($connection,$_POST['Jobx'][$cnt]); 
          $Duration = $_POST['Durationx'][$cnt]; 
          $Title = mysqli_real_escape_string($connection,$_POST['Titlex'][$cnt]); 
          $Role = mysqli_real_escape_string($connection,$_POST['Rolex'][$cnt]);         
          $SqlCommand = "INSERT INTO workexperience (UserId, WorkNo, CompanyCityCountry, Duration, Title, Role) 
                        VALUES ('$UserId', '$WorkNo', '$CompanyCityCountry', '$Duration', '$Title', '$Role' )";     
          $res = mysqli_query($connection, $SqlCommand);
          
          if(!$res){
            echo "Error description: " . mysqli_error($connection);
            exit;
          }      
          
          $cnt++;
        }     
        
        // Insert Skill
        $cnt = 0;
        //while (isset($_POST['Skill'][$cnt])) {
        while ($cnt < 150) {
          if(!isset($_POST['Skill'][$cnt])) {
            $cnt++;
            continue;
          } 
          $SkillName = $_POST['Skill'][$cnt];
          $ExpertLevel = $_POST['Lvl'][$cnt][0];
          
          $SqlCommand = "INSERT INTO employeeskills (UserId, SkillName, ExpertLevel) 
                        VALUES ('$UserId', '$SkillName', '$ExpertLevel' )";            
          echo "Skill:".$cnt." ".$_POST['Skill'][$cnt]." Level:".$cnt." ".$_POST['Lvl'][$cnt][0]."\r\n";

          $res = mysqli_query($connection, $SqlCommand);          
          
          if(!$res){
            echo "Error description: " . mysqli_error($connection);
            exit;
          }   
          
          $cnt++;
        }
        header("location: login.php"); 
      }else{
        $error = "Error description: " . mysqli_error($connection);
        exit;
      }
    }else{
      $error = "Error description: " . mysqli_error($connection);
      exit;
	  }
    header("location: login.php"); 

  }
//echo "result:" . $error;

?>
