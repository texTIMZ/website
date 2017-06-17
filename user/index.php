<?php
//default screen telling user about his/her details 
//and having links for viewing/adding/editing items

session_start();
require_once ('./functions.php');
check_login();

$user = get_user_details();
//var_dump($user);
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-yw4l" colspan="2">Your Details</th>
  </tr>
  <tr>
    <td class="tg-6k2t">First Name</td>
    <td class="tg-6k2t"><?php echo $user['s_fname']?></td>
  </tr>
  <tr>
    <td class="tg-yw4l">Last Name</td>
    <td class="tg-yw4l"><?php echo $user['s_lname']?></td>
  </tr>
  <tr>
    <td class="tg-6k2t">Phone No</td>
    <td class="tg-6k2t"><?php echo $user['s_phno']?></td>
  </tr>
  <tr>
    <td class="tg-yw4l">Email</td>
    <td class="tg-yw4l"><?php echo $user['s_email']?></td>
  </tr>
  <tr>
    <td class="tg-6k2t">DOB</td>
    <td class="tg-6k2t"><?php echo $user['s_dob']?></td>
  </tr>
</table></div>
<br /><br />
<a href="./edit_user.php">Edit Your Details</a>
<br /><br />
<a href="data.php?i=1">News Item</a>
<br><br>
<a href="data.php?i=2">Events</a>
<br><br>
<a href="data.php?i=3">Jobs</a>
<br><br>
<a href="data.php?i=4">Newsweave</a>
<br /><br />
<a href="data.php?i=5">Extras</a>

