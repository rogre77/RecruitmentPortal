<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Modal</title>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />	
  <script>
$(document).on("click", ".open-homeEvents", function () {
     var eventId = "test";
     document.getElementById("txt").innerHTML = "Changed";
  $('.modal-title').html("name");   
  $('#idHolder').html( "eventId" );
   $('.modal-body-inner').html("course");
  $('#modalHomeEvents').show();
});
  </script>
</head>
<body>
<button class="open-homeEvents btn btn-primary" data-id="2014-123456"  data-toggle="modal" data-target="#modalHomeEvents">More Details</button>	
<div>
     <input type="text" class="form-control data" id="Idcc" name="cc" value="test">
</div>  
<div id="modalHomeEvents" class="modal fade" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="height:50px;">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <p id="txt">Some text in the Modal..</p>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Login" name="login" style="background-color:rgb(0,30,66); ">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
      </div>

    </div>
  </div>	
<script src="https://code.jquery.com/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>

