<?php
  session_start();
  // Date in the past
  //header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
  header('Cache-Control: no-store, no-cache, must-revalidate'); 
  header('Cache-Control: post-check=0, pre-check=0', FALSE); 
  header('Pragma: no-cache'); 

  unset($_SESSION["UserId"]);
  unset($_SESSION["User"]);
  unset($_SESSION["Type"]);

  //here I am destroying the session
  session_destroy();
  header("Location: index.php");

  //Absolute killing
  // header("Location: login.php",TRUE,302);
  // die();

?>


