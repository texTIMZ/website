<?php
//to edit user details by the user

session_start();
require_once ('./functions.php');
check_login();

$user = get_user_details();

if(isset($_POST['submit']))
{
    //$user = get_user_details();
    $_POST['fname'] = htmlspecialchars($_POST['fname']);
    $_POST['fname'] = htmlspecialchars($_POST['fname']);
    $_POST['phno'] = htmlspecialchars($_POST['phno']);
    $_POST['email'] = htmlspecialchars($_POST['email']);
    $_POST['dob'] = htmlspecialchars($_POST['dob']);
    
    if(isset($_POST['newpass']) && strlen($_POST['newpass'])>0) {
        $pass = sha1($_POST['newpass'] . "salt");
    }
    else {
        $pass = htmlspecialchars($_SESSION['pass']);         
    }
    
    $uid = intval(htmlspecialchars($user['pk_i_id']));
    
    $stmt = $conn->prepare("UPDATE t_user SET s_fname = :fname , s_lname = :lname , s_phno = :phno , s_email = :email ,s_pass = :pass , s_dob = :dob WHERE pk_i_id = :uid"); 
    $stmt->bindParam(':fname', $_POST['fname']);
    $stmt->bindParam(':lname', $_POST['lname']);
    $stmt->bindParam(':phno', $_POST['phno']);
    $stmt->bindParam(':email', $_POST['email']); 
    $stmt->bindParam(':dob', $_POST['dob']);
    $stmt->bindParam(':pass', $pass);
    $stmt->bindParam(':uid', $uid);    
    $stmt->execute();
    
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['pass'] = $pass;
    header('Location: ./index.php');
    exit();
    
}




?>

<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>
<div class="tg-wrap">
<form method="post" action="./edit_user.php">
<table class="tg">
  <tr>
    <th class="tg-yw4l" colspan="2">Your Details</th>
  </tr>
  <tr>
    <td class="tg-6k2t">First Name</td>
    <td class="tg-6k2t"><input type="text" name="fname" id="fname" value="<?php echo $user['s_fname'];?>"/></td>
  </tr>
  <tr>
    <td class="tg-yw4l">Last Name</td>
    <td class="tg-yw4l"><input type="text" name="lname" id="lname" value="<?php echo $user['s_lname'];?>"/></td>
  </tr>
  <tr>
    <td class="tg-6k2t">Phone No</td>
    <td class="tg-6k2t"><input type="text" name="phno" id="phno" value="<?php echo $user['s_phno'];?>"/></td>
  </tr>
  <tr>
    <td class="tg-yw4l">Email</td>
    <td class="tg-yw4l"><input type="text" name="email" id="email" value="<?php echo $user['s_email'];?>"/></td>
  </tr>
  <tr>
    <td class="tg-6k2t">DOB</td>
    <td class="tg-6k2t"><input type="dob" name="dob" id="dob" value="<?php echo $user['s_dob'];?>"/></td>
  </tr>
  <tr>
    <td class="tg-yw4l">New Password</td>
    <td class="tg-yw4l"><input type="text" name="newpass" id="newpass" placeholder ="Leave Blank for no change" style="width: 200;"/></td>
  </tr>  
</table>
<br />
<input type="submit" name="submit" value="Update Values"/></form></div>
<br /><br />