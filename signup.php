<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<title>Sign Up!</title>
</head>
<body>
	<br>	<br>
	<center>
	<form method="post" action="validinput.php">
<div class="panel panel-primary" style="width:500px;">
        <div class="panel-heading" style="height:45px; "><h3 style="position: relative;bottom:20px;">SIGN UP</h3></div><br>

        <div class="panel-body">
        <input class="form-group form-control" type="text" placeholder="Name" name="Name"  style="width:250px;">
        <input class="form-group form-control" type="text" placeholder="Email" name="Email"  style="width:250px;">
        <input class="form-group form-control" type="Password" placeholder="Password" pattern=".{6,}" name="Password" value="" style="width:250px;">
        <input class="form-group form-control" type="Password" placeholder="Confirm Password" pattern=".{6,}" name="cPassword" value="" style="width:250px;">
        <input type="submit" class="btn btn-primary" value="Submit" style="width:250px;">
        </div>

        <div class="panel-footer"><a href="signin.php"><div class="btn btn-primary">Already have an account? Login</div>
</div>
</form>
</center>
</body>
</html>

