<?php
  //clear cache
  //header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 
  session_start();

  //load and initialize user class
  include 'User.php';
  $user = new User();

  if(isset($_POST['btnForgotSubmit'])){
      //check whether email is empty
      if(!empty($_POST['email'])){
          //check whether user exists in the database
          $prevCon['where'] = array('email'=>$_POST['email']);
          $prevCon['return_type'] = 'count';
          $prevUser = $user->getRows($prevCon);
          if($prevUser > 0){
              //generat unique string
              $uniqidStr = md5(uniqid(mt_rand()));;

              //update data with forgot pass code
              $conditions = array(
                  'email' => $_POST['email']
              );
              $data = array(
                  'Forgot_Psw_Id' => $uniqidStr
              );
              $update = $user->update($data, $conditions);

              if($update){
                  $resetPassLink = 'http://localhost/RecruitmentPortal/ResetPassword.php?fp_code='.$uniqidStr;
                  //get user details
                  $con['where'] = array('email'=>$_POST['email']);
                  $con['return_type'] = 'single';
                  $userDetails = $user->getRows($con);

                  // send mail
                  $the_sender = "GradForce Web Recruitment Portal Auto-Mailer";
                  $the_subject = "Password Update Request";
                  $the_message = 'Dear '.$userDetails['FirstName'].', 
                  <br/>Recently a request was submitted to reset a password for your account. If this was a mistake, just ignore this email and nothing will happen.
                  <br/>To reset your password, visit the following link: <a href="'.$resetPassLink.'">'.$resetPassLink.'</a>
                  <br/><br/>Regards,
                  <br/>GradForce Admin';
                  $the_tomail = $userDetails['Email'];
                  $the_toname = "";
                  $the_cc = "";

                  include("mailer/mail1.php");                

                  if($error=="") {
                    $sessData['status']['type'] = 'success';
                    $sessData['status']['msg'] = 'Please check your e-mail, we have sent a password reset link to your registered email.';
                  } else {
                    $sessData['status']['type'] = 'error';
                    $sessData['status']['msg'] = 'E-Mail failed to be sent, please try again.';                    
                  }
              }else{
                  $sessData['status']['type'] = 'error';
                  $sessData['status']['msg'] = 'Some problem occurred, please try again.';
              }
          }else{
              $sessData['status']['type'] = 'error';
              $sessData['status']['msg'] = 'Given email is not associated with any account.'; 
          }

      }else{
          $sessData['status']['type'] = 'error';
          $sessData['status']['msg'] = 'Enter email to create a new password for your account.'; 
      }
      //store reset password status into the session
      $_SESSION['sessData'] = $sessData;
      //redirect to the forgot pasword page
      header("Location:forgotPassword.php");
  }elseif(isset($_POST['btnResetSubmit'])){
      $fp_code = '';
      if(!empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['fp_code'])){
          $fp_code = $_POST['fp_code'];
  
          if(strlen($_POST['password']) < 8){
              $sessData['status']['type'] = 'error';
              $sessData['status']['msg'] = 'Password length should not be less than 8.';             
          } else {
              //password and confirm password comparison
              if($_POST['password'] !== $_POST['confirm_password']){
                  $sessData['status']['type'] = 'error';
                  $sessData['status']['msg'] = 'Confirm password must match with the password.'; 
              }else{
                  //check whether identity code exists in the database
                  $prevCon['where'] = array('Forgot_Psw_Id' => $fp_code);
                  $prevCon['return_type'] = 'single';
                  $prevUser = $user->getRows($prevCon);
                  if(!empty($prevUser)){
                      //update data with new password
                      $conditions = array(
                          'Forgot_Psw_Id' => $fp_code
                      );
                      $data = array(
                          'password' => md5($_POST['password']),
                          'Forgot_Psw_Id' => ""
                      );
                      $update = $user->update($data, $conditions);
                      if($update){
                          $sessData['status']['type'] = 'success';
                          $sessData['status']['msg'] = 'Your account password has been reset successfully. Please login with your new password.';
                      }else{
                          $sessData['status']['type'] = 'error';
                          $sessData['status']['msg'] = 'Some problem occurred, please try again.';
                      }
                  }else{
                      $sessData['status']['type'] = 'error';
                      $sessData['status']['msg'] = 'You are not authorized to reset new password of this account.';
                  }
              }
          }
      }else{
          $sessData['status']['type'] = 'error';
          $sessData['status']['msg'] = 'All fields are mandatory, please fill all the fields.'; 
      }
      //store reset password status into the session
      $_SESSION['sessData'] = $sessData;
      $redirectURL = ($sessData['status']['type'] == 'success')?'index.php':'resetPassword.php?fp_code='.$fp_code;
      //redirect to the login/reset pasword page
      header("Location:".$redirectURL);
  }

?>