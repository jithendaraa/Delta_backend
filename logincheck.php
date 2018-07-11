<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
    if(isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response'])
    {
        //var_dump($_POST);
        //$secret = "6Lf8qGMUAAAAAJngwDpthykrPIDOtRDrxsW_Y8cA";
        //$ip = $_SERVER['REMOTE_ADDR'];
        //$captcha = $_POST['g-recaptcha-response'];
         //$rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha&remoteip$ip");
        //var_dump($rsp);
        //$arr = json_decode($rsp,TRUE);
        //if($arr['success']){
        //    echo "Done";
        //}
        //else echo "no";
        
            $con=mysqli_connect("localhost","jith","Abhinav1234","delta")
    or die(mysqli_error($con));
    $id=$_POST['lemail'];
    $pwd=$_POST['lpassword'];
    $datau=mysqli_query($con,"SELECT COUNT(*) AS num FROM userlogin WHERE email='$id'") ;
    $row = mysqli_fetch_assoc($datau);

    if($row['num'] != 0)
    {
        $datap=mysqli_query($con,"SELECT password FROM userlogin WHERE email='$id'");
        $result=mysqli_fetch_assoc($datap);
        if($result['password'] == md5("$pwd"))
        {
            $username_query=mysqli_query($con,"SELECT username FROM userlogin WHERE email='$id'");
            $username=mysqli_fetch_assoc($username_query);
            session_start();
            $_SESSION['username']=$username['username'];
            header("location: home.php");
        }
        else
            {
                echo "Invalid password - fail1";
            }
    }
    else
    {
        echo "Invalid Email";
    }

    }
	?>
</body>
</html>
