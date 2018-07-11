<?php


session_start();
    if(isset($_SESSION['username']))
    $username=$_SESSION['username'];
     else 
    header("location:signin.php");

$con=mysqli_connect("localhost","jith","Abhinav1234","delta")
    or die(mysqli_error($con));

class calendar{
  
  public $month;
  public $year;
  public $days_of_week;
  public $day_of_week;
  public $days_in_month;
  public $date_info;

  public function __construct($month, $year, $days_of_week=array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'))
  {
    $this->month = $month;
    $this->year = $year;
    $this->days_of_week = $days_of_week;
    $this->days_in_month = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
    $this->date_info = getdate(mktime(0, 0, 0, $this->month, 1, $this->year));
    $this->day_of_week = $this->date_info['wday'];
  }

  public function prev_display()
  {}
   
  public function next_display()
  {} 


  public function display()
  {
    $current_day = 1;
    $output = '<div class="container"><table class="calendar">';
    $output .= '<caption>' . $this->date_info['month'].' '.$this->year.'</caption>';
    $output .= '<tr>';
    for($i=0; $i<7; $i++)
    {
     $output .= '<th class="title"><center>'.$this->days_of_week[$i].'</center></th>';
    }
    $output .= '</tr><tr>';

    if($this->day_of_week>0)
      $output .= '<th class="colspan" colspan=" '. $this->day_of_week .'"></th>'; //prev_display()

    while($current_day <= $this->days_in_month )
    { 
        if($this->day_of_week == 7)
        {
          $this->day_of_week = 0;
          $output .= '</tr><tr>';
        }  
          $output .= ' <td><a style="color:white;" onmousehover="showDiv()" href="home.php?day= '. $current_day . ' "> ';
          $output .= '<center>'.$current_day.'</center>';
          $output .= '</a></td>';
          $this->day_of_week += 1;
          $current_day += 1;
    }
    if($this->day_of_week != 7)
    {
        $rem=7-$this->day_of_week;
        $output .= '<th class="colspan" colspan=" '. $rem .'"></th>'; //next_display()
    }

    $output .= '</table></div><br>';
    echo $output;
  }

}




if(isset($_POST['submit']))
{
    $_SESSION['month'] = $_POST['month'];
    $_SESSION['year'] = $_POST['year'];
}


if(!isset($_SESSION['month']))
{
    
    $_SESSION['month'] = 6;
    $_SESSION['year'] = 2018;
}
 
 $current_month=$_SESSION['month'];
 $current_year=$_SESSION['year'];

$calendar = new calendar($current_month,$current_year);
$calendar->display();
?>
<div class="padding"><b>
<?php     
if(isset($_POST['entry']))
{
   
    $flag=0;
    $date = $_POST['date'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_h = $_POST['start_h'];
    $end_h = $_POST['end_h'];
    if(isset($_POST['start_m']))
    $start_m = $_POST['start_m'];
    else
        $start_m = 0;
    if(isset($_POST['end_m']))
    $end_m = $_POST['end_m'];
    else 
        $end_m = 0;
    $abs_start= ($start_h)*60 +$start_m;
    $abs_end= ($end_h)*60 +$end_m;
    $no_entries=mysqli_query($con,"SELECT COUNT(*) AS no FROM appoint WHERE dates='$date' AND month='$month' AND year='$year' AND username='$username'") ;
    $entries = mysqli_fetch_assoc($no_entries);
    if($entries['no']>0)
    {
      $insert_check=mysqli_query($con,"SELECT * FROM appoint WHERE dates='$date' AND username='$username' AND month='$month' AND year='$year'  ");
      while($row=mysqli_fetch_array($insert_check))
       {
          $entry_start = ($row['start_h']*60)+$row['start_m'];
          $entry_end = ($row['end_h']*60)+$row['end_m'];
          if(($abs_start >= $entry_start && $abs_start < $entry_end) || ($abs_start < $entry_start && $abs_end > $entry_start ))
         {
           $flag=1;
         }
       }
    }
    if ($flag == 0) 
    {
       if(mysqli_query($con,"INSERT INTO appoint (username,dates,month,year,title,description,start_h,start_m,end_h,end_m) VALUES ('$username','$date','$month','$year','$title','$description','$start_h','$start_m','$end_h','$end_m') "))
       echo "New Appointment created successfully";
    }
    else
        echo "Appointment not created because it clashes with another appointment on this date!";
} ?></b><?php

if(isset($_POST['entry2']))
{
    $invite_from = $username;
    $invite_to = $_POST['invite_to'];
    $a_date = $_POST['a_date'];
    $a_month = $_POST['a_month'];
    $a_year = $_POST['a_year'];
    $a_title = $_POST['a_title'];
    $a_description = $_POST['a_description'];
    $a_start_h = $_POST['a_start_h'];
    $a_start_m = $_POST['a_start_m'];
    $a_end_h = $_POST['a_end_h'];
    $a_end_m = $_POST['a_end_m'];

    if(mysqli_query($con,"INSERT INTO invite (invite_from,invite_to,a_dates,a_month,a_year,a_title,a_description,a_start_h,a_start_m,a_end_h,a_end_m) VALUES ('$invite_from','$invite_to','$a_date','$a_month','$a_year','$a_title','$a_description','$a_start_h','$a_start_m','$a_end_h','$a_end_m')"))
        echo "Invite sent!";
    else
        echo "Error";

}?></div><?php

if(isset($_GET['day']))
{
    $date = $_GET['day'];
    $no_entries=mysqli_query($con,"SELECT COUNT(*) AS num FROM appoint WHERE dates='$date' AND month='$calendar->month' AND year='$calendar->year' AND username='$username'") ;
    $entries = mysqli_fetch_assoc($no_entries); ?>
    <div class="contain"><b> <?php
    echo "Number of appointments under day".$_GET['day']."=".$entries['num']; ?></b></div><br>

<?php
    if($entries['num'] > 0)
    {
       $get_entries = mysqli_query($con,"SELECT * FROM appoint WHERE dates='$date' AND month='$calendar->month' AND year='$calendar->year' AND username='$username'");
       while($row_result = mysqli_fetch_assoc($get_entries))
       { ?> 

          <div class="entries">
          <p><b class="details">Date details of appointment:</b><?php echo $date."-".$current_month."-".$current_year;?><br>
           <b class="details">Title:</b><?php echo $row_result['title'];?><br>
           <b class="details">Description:</b><?php echo $row_result['description'];?><br>
           <b class="details">From:</b>
          <?php 
         if($row_result['start_h'] < 10)
           {
            if($row_result['start_m'] < 10)
            echo "0".$row_result['start_h'].":0".$row_result['start_m'];
            else if($row_result['start_m'] >= 10)
            echo "0".$row_result['start_h'].":".$row_result['start_m'];
           }
           else if($row_result['start_h'] >= 10)
            {
             if($row_result['start_m'] < 10)
             echo $row_result['start_h'].":0".$row_result['start_m'];
             else if($row_result['start_m'] >= 10)
             echo $row_result['start_h'].":".$row_result['start_m'];
            }
          ?><br>
          <b class="details">To:</b>
          <?php 
           if($row_result['end_h'] < 10)
           {
            if($row_result['end_m'] < 10)
            echo "0".$row_result['end_h'].":0".$row_result['end_m'];
            else if($row_result['end_m'] >= 10)
            echo "0".$row_result['end_h'].":".$row_result['end_m'];
           }
           else if($row_result['end_h'] >= 10)
            {
             if($row_result['end_m'] < 10)
             echo $row_result['end_h'].":0".$row_result['end_m'];
             else if($row_result['end_m'] >= 10)
             echo $row_result['end_h'].":".$row_result['end_m'];
            }?></div><?php
        }
          ?>
         </p>

          <?php
    }
    else if($entries['num'] == 0)
    {
        ?> <div class="no-entry">No appointments on this day</div> <?php
    }  
} 


//$invites=mysqli_query($con,"SELECT COUNT (*) AS inv FROM invite WHERE invite_to='$username'");
//$invites_result = mysqli_fetch_assoc($invites);
//if($invites_result > 0)
//{
   //get invites
   $get_invite=mysqli_query($con,"SELECT * FROM invite WHERE invite_to='$username'");
   
//}

if(isset($_GET['acc_desc'])){

    $acc_desc=$_GET['acc_desc'];
    $copy=mysqli_query($con,"SELECT * FROM invite WHERE invite_to='$username' AND a_description='$acc_desc'");
    $res=mysqli_fetch_assoc($copy);
    $res_title=$res['a_title'];
    $res_description=$res['a_description'];
    $res_dates=$res['a_dates'];
    $res_month=$res['a_month'];
    $res_year=$res['a_year'];
    $res_start_h=$res['a_start_h'];
    $res_start_m=$res['a_start_m'];
    $res_end_h=$res['a_end_h'];
    $res_end_m=$res['a_end_m'];
    if(mysqli_query($con,"INSERT INTO appoint (username,title,description,dates,month,year,start_h,start_m,end_h,end_m) VALUES ('$username','$res_title','$res_description','$res_dates','$res_month','$res_year','$res_start_h','$res_start_m','$res_end_h','$res_end_m')"))
        echo "Invite accepted ".$acc_desc;
     $del_invite=mysqli_query($con,"DELETE FROM invite WHERE invite_to='$username' AND a_description='$acc_desc'");
     header("location: home.php ");
}

if(isset($_GET['del_desc'])){

    $del_desc=$_GET['del_desc'];
    $del_invite=mysqli_query($con,"DELETE FROM invite WHERE invite_to='$username' AND a_description='$del_desc'");
     header("location: home.php ");

}




$count=mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) AS invrow FROM invite WHERE invite_to='$username'"));
echo $count['invrow'];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="calendars.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <title></title>
</head>
<body style=" background-color: #FF9966; "><br>
<div class="nav_calendar">
    <form method="post" action="home.php">
        <input class="form form-group form-control" name="month" placeholder="Month(mm)" value="<?php echo $current_month; ?>" style="width:15%;">
        <input class="form form-group form-control" name="year" placeholder="Year(yyyy)" value="<?php echo $current_year; ?>" style="width:15%;">
        <input type="submit" class="btn btn-primary" value="Submit" name="submit">
    </form>
<br><br>
       
       <table class="inv_table"> 
       <?php if($count['invrow'] > 0) { ?>   
                  <tr class="inv_tr">
                      <th class="inv_th">Invite from</th>  
                      <th class="inv_th">Date of meeting</th>
                      <th class="inv_th">Title</th>
                      <th class="inv_th">Description</th>
                      <th class="inv_th">Start time</th>
                      <th class="inv_th">End time</th>
                      <th class="inv_th">Action 1</th>
                      <th class="inv_th">Action 2</th>
                  </tr>
              <?php } else if($count['invrow'] == 0) { ?>
                      <p class="no_inv">No invites!</p>
          <?php } $i=1;

            while($get_invite_result=mysqli_fetch_assoc($get_invite))
            {?><tr class="inv_tr">
                   <td class="inv_td"><?php echo $get_invite_result['invite_from']; 
                        ?> 
                   </td>
                   <td class="inv_td"><?php $a_date = $get_invite_result['a_dates'];
                         $a_month = $get_invite_result['a_month'];
                         $a_year = $get_invite_result['a_year'];
                         if($a_date < 10)
                         $a_date = "0".$a_date;
                         if($a_month < 10)
                         $a_month = "0".$a_month;
                         echo $a_date."-".$a_month."-".$a_year;
                        ?>    
                   </td>
                   <td class="inv_td"><?php echo $get_invite_result['a_title']; 
                        ?>    
                   </td>
                   <td class="inv_td"><?php echo $get_invite_result['a_description'];
                        ?>      
                   </td>
                   <td class="inv_td"><?php if($get_invite_result['a_start_h'] > 9)
                             {
                             if($get_invite_result['a_start_m'] > 9)
                                 echo $get_invite_result['a_start_h'].":".$get_invite_result['a_start_m'];
                             else
                                 echo $get_invite_result['a_start_h'].":0".$get_invite_result['a_start_m']; 
                             }
                             else if ($get_invite_result['a_start_h'] < 10)
                             {
                             if($get_invite_result['a_start_m'] > 9)
                                 echo "0".$get_invite_result['a_start_h'].":".$get_invite_result['a_start_m'];
                             else
                                 echo "0".$get_invite_result['a_start_h'].":0".$get_invite_result['a_start_m'];
                             }  
                        ?>    
                   </td>
                   <td class="inv_td"><?php if($get_invite_result['a_end_h'] > 9)
                             {
                             if($get_invite_result['a_end_m'] > 9)
                                 echo $get_invite_result['a_end_h'].":".$get_invite_result['a_end_m'];
                             else
                                 echo $get_invite_result['a_end_h'].":0".$get_invite_result['a_end_m']; 
                             }
                             else if ($get_invite_result['a_end_h'] < 10)
                             {
                             if($get_invite_result['a_end_m'] > 9)
                                 echo "0".$get_invite_result['a_end_h'].":".$get_invite_result['a_end_m'];
                             else
                                 echo "0".$get_invite_result['a_end_h'].":0".$get_invite_result['a_end_m'];
                             }  
                        ?>
                   </td>
                   <td class="inv_td"><form method="post" action="home.php?acc_desc=<?php echo $get_invite_result['a_description']; ?>" style="padding-top: 18px; "><button type="submit" name="acc-btn" class="coolbtn">Accept</button></form>
                   </td>
                   <td class="inv_td"><form method="post" action="home.php?del_desc=<?php echo $get_invite_result['a_description']; ?>" style="padding-top: 18px; "><button type="submit" name="del-btn" class="coolbtn">Decline</button></form>
                   </td>
                    

               </tr>        
          <?php  $i+=1;} ?>
       </table>
</div><br><br>

    

    <div style="padding-left: 100px;">
    <button id="myBtn" class="make-appt-btn"><span class="glyphicon glyphicon-plus"></span>Create an appointment</button>
    <button id="myBtn2" class="send-inv"><span class="glyphicon glyphicon-plus"></span>Send Invite</button>
    
    </div>
    <b style="float: right;">Logged in as: <?php echo $username;?></b>




























<!--modal1-->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
        <b>Logged in as: <?php echo $username;?></b>
        <span class="close">&times;</span>
        <h2>Create an Appointment</h2>
    </div>
    <div class="modal-body">
            <form method="post" action="home.php">    
              <input class="form form-group form-control" type="text" name="date" placeholder="Date(dd)" style="width:70%;">
              <input class="form form-group form-control" type="text" name="month" placeholder="Month(mm)" style="width:70%;">
              <input class="form form-group form-control" type="text" name="year" placeholder="year(yyyy)" style="width:70%;">
              <input class="form form-group form-control" type="text" name="title" placeholder="Title" style="width:70%;">
              <input class="form form-group form-control" type="text" placeholder="Event description" name="description" style="width:70%;">
              <input class="form form-group form-control" type="text" name="start_h" placeholder="Start time(hh):" style="width:70%;">
              <input class="form form-group form-control" type="text" name="start_m" placeholder="Start time(mm):" style="width:70%;">
              <input class="form form-group form-control" type="text" name="end_h" placeholder="End time(hh):" style="width:70%;">
              <input class="form form-group form-control" type="text" name="end_m" placeholder="End time(mm):" style="width:70%;">       
    </div>
    <div class="modal-footer">
      <input type="submit" class="btn btn-primary" name="entry" value="Submit">
      </form>
    </div>
  </div>
</div>

<script>
var modal = document.getElementById('myModal');
var btn = document.getElementById("myBtn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
    modal.style.display = "block";
}
span.onclick = function() {
    modal.style.display = "none";
}

</script>


<!--modal2-->
<div id="myModal2" class="modal2">
  <!-- Modal content -->
  <div class="modal-content2">
    <div class="modal-header2">
        <b>Logged in as: <?php echo $username;?></b>
        <span class="close2">&times;</span>
        <h2>Send an invite</h2>
    </div>
    <div class="modal-body2">
            <form method="post" action="home.php">
              <input type="text" name="invite_to" class="form form-group form-control" placeholder="Invite to" style="width: 70%;">
              <input type="text" name="a_date" class="form form-group form-control" placeholder="Appointment date" style="width: 70%;">
              <input type="text" name="a_month" class="form form-group form-control" placeholder="Appointment month" style="width: 70%;">
              <input type="text" name="a_year" class="form form-group form-control" placeholder="Appointment year" style="width: 70%;">
              <input type="text" name="a_title" class="form form-group form-control" placeholder="Title" style="width: 70%;">
              <input type="text" name="a_description" class="form form-group form-control" placeholder="Description" style="width: 70%;">
              <input type="text" name="a_start_h" class="form form-group form-control" placeholder="Start time(hh):" style="width: 70%;">
              <input type="text" name="a_start_m" class="form form-group form-control" placeholder="Start time(mm):" style="width: 70%;">
              <input type="text" name="a_end_h" class="form form-group form-control" placeholder="End time(hh):" style="width: 70%;">
              <input type="text" name="a_end_m" class="form form-group form-control" placeholder="End time(mm):" style="width: 70%;">
    </div>
    <div class="modal-footer2">
      <input type="submit" class="btn btn-primary" name="entry2" value="Submit">
      </form>
    </div>
  </div>
</div>


<script>
var modal2 = document.getElementById('myModal2');
var btn2 = document.getElementById("myBtn2");
var span2 = document.getElementsByClassName("close2")[0];
btn2.onclick = function() {
    modal2.style.display = "block";
}
span2.onclick = function() {
    modal2.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
    if (event.target == modal2) {
        modal2.style.display = "none";
    }
    if (event.target == modal3) {
        modal3.style.display = "none";
    }
}
</script>
<center>
<!--modal3-->



</body>
</html>

