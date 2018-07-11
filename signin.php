<!DOCTYPE html>
<html>
<head>
	<title>Sign In!</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src='https://www.google.com/recaptcha/api.js'></script>

</head>
<body>
<br><br>
<form method="post" action="logincheck.php">
	<center>
	<div class="panel panel-primary" style="width:500px;">
		<div class="panel-heading" style="height:45px;"><center><h3 style="position: relative;bottom: 20px;">Login</h3></center></div>


		<div class="panel-body">
			<b>Email:</b><input class="form form-group form-control" type="text" name="lemail" style="width:250px;"><b>Password:</b><input class="form form-group form-control" type="password" name="lpassword" style="width: 250px;"><div class="g-recaptcha" data-sitekey="6Lf8qGMUAAAAAFhVhDH_UhUer74FQv9l6usyZINc"></div><br><input type="submit" value="Login" class="btn btn-primary" style="width: 227px;"><br>

		</div>

		<div class="panel-footer"><a href="signup.php"><div class="btn btn-primary">Don't have an account? Register</div></div>
	</div>
    </center>
</form>
</body>
</html>
