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
$sql = "SELECT pk_i_id , s_headline , s_create_time FROM t_news_item where b_active = 1 ORDER BY pk_i_id DESC LIMIT $first_record , 10";
$stmt = $conn->prepare($sql);
$stmt->execute();

$i=0;
?>
<h1>All News Items</h1>
<a href="../user">Home</a><br><br>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-yw4l" >News Item Id</th>
    <th class="tg-yw4l" >Title</th>
    <th class="tg-yw4l" >Creation Time</th>
  </tr>
<?php while($res = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
  <tr>
    <td class="tg-6k2t"><?php echo $news_id=$res['pk_i_id'];?></td>
    <td class="tg-6k2t"><?php echo "<a href = './item.php?id=$news_id'>".$res['s_headline']."</a>";?></td>
    <td class="tg-6k2t"><?php echo $res['s_create_time'];?></td>
  </tr>
  <?php } ?>

</table></div>
<br />
<?php if(isset($_GET['page']) && $_GET['page']>1) {?>
<a href="./items.php?page=<?php echo $_GET['page'] - 1;  ?>">Previous Page</a> &nbsp; &nbsp;
<?php } ?>
<?php 
$sql = "SELECT pk_i_id FROM t_news_item where b_active = 1 and pk_i_id < $news_id ";
$stmt = $conn->prepare($sql);
$stmt->execute();
if($stmt->fetch(PDO::FETCH_ASSOC)) {
?>
<a href="./items.php?page=<?php echo ($first_record/10) + 2; ?>">Next Page</a>
<?php } ?>
<br /><br />
<a href="./item.php">Add a new Item</a>