<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>

<body>
	<?php
    $Name = $_POST["Name"];
    $Email= $_POST["Email"];
    $Password = $_POST["Password"];
    $cPassword = $_POST["cPassword"];
  if($cPassword==$Password && $Name !== "" && $Email !== "" && $Password !== "")
  {
    $con=mysqli_connect("localhost","jith","Abhinav1234","delta")
  or die(mysqli_error($con));
  $sql = "INSERT INTO userlogin (username, email, password) VALUES ('$Name','$Email',MD5('$Password'))";
    if (mysqli_query($con, $sql))
        header("location:signin.php");
    else
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
  }
  else
    echo "Invalid input. Go back and try again";

?>
</body>
</html>
