<?php
//to display all news items

session_start();
require_once ('./functions.php');
check_login();
if(isset($_GET['page'])) {
    $first_record = ($_GET['page']-1)*10;
}
else {
    $first_record = 0;
}
$sql = "SELECT pk_i_id , event_name, date_start, create_time FROM t_events ORDER BY pk_i_id DESC LIMIT $first_record , 10";
$stmt = $conn->prepare($sql);
$stmt->execute();

//$i=0;
?>
<h1>All Event Items</h1>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-yw4l" >Event Id</th>
    <th class="tg-yw4l" >Event Name</th>
    <th class="tg-yw4l" >Date Start</th>
    <th class="tg-yw4l" >Time Created</th>    
    
  </tr>
<?php while($res = $stmt->fetch(PDO::FETCH_ASSOC)) { echo "wordwrap"; ?>
  <tr>
    <td class="tg-6k2t"><?php echo $event_id=$res['pk_i_id'];?></td>
    <td class="tg-6k2t"><?php echo "<a href = './event.php?id=$event_id'>".$res['event_name']."</a>";?></td>
    <td class="tg-6k2t"><?php echo $res['date_start'];?></td>
    <td class="tg-6k2t"><?php echo $res['create_time'];?></td>

  </tr>
  <?php } ?>

</table></div>
<br />
<?php if(isset($_GET['page']) && $_GET['page']>1) {?>
<a href="./events.php?page=<?php echo $_GET['page'] - 1;  ?>">Previous Page</a> &nbsp; &nbsp;
<?php } ?>
<?php 
$sql = "SELECT pk_i_id FROM t_events where pk_i_id < $event_id ";
$stmt = $conn->prepare($sql);
$stmt->execute();
if($stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<a href="./events.php?page=<?php echo ($first_record/10) + 2; ?>">Next Page</a>
<?php } ?>
<br /><br />
<a href="./event.php?event=true">Add a new Event</a>