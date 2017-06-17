<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

<?php
//to create, edit, delete(inactivate) a single ad ad

/**
$_POST[ad_id] - 0/false or not set ->new ad
$_POST[edit] - 1/true ->view/edit an ad
        $_POST[edit] - 0/false or not set ->view
        $_POST[edit] - 1/true ->edit
*/

session_start();
require_once ('./functions.php');
check_login();
if(isset($_GET['id'])) {
    $ad_id = intval($_GET['id']);
    $new_ad = false;
        
    $res = get_ad_details($ad_id);
    if($res == null) {
        echo "Invalid ID No";
        exit();
    }
    if(isset($_GET['edit'])) {
        if($_GET['edit']=='true') {
            $edit = true;
            $title = "Edit ad #$ad_id";
        }
        else {
            $edit = false;
            $title = "View ad #$ad_id";
        }        
    }
    else {
        $edit = false;
        $title = "View ad #$ad_id";
    }
    
    $sql = "SELECT s_source FROM t_media_ads where fk_i_item_id = $ad_id ORDER BY pk_i_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($img= ($stmt->fetch(PDO::FETCH_ASSOC)));
        $img_loc = $img['s_source'];
}
else {
    $new_ad = true;
    $edit = true;
    $title = "Add a new ad";
}
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>

<h1><?php echo $title;?></h1>
<form method="POST" action="./campaign_action.php" enctype="multipart/form-data">
<?php if($new_ad) { ?><input type="hidden" name="is_new" id="is_new" value="true"/><?php }?>
<?php if(isset($ad_id)) { ?><input type="hidden" name="ad_id" id="ad_id" value="<?php echo $ad_id;?>"/><?php } ?>
<div class="tg-wrap"><table class="tg">
    <th class="tg-yw4l" >Campaign name</th>
    <th class="tg-yw4l" ><input type="text" name="Campaign_name" id="Campaign_name" style="width: 400;" <?php if(!$new_ad) echo "value='".$res['Campaign_name']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-yw4l" >Date Start</th>
    <th class="tg-yw4l" ><input type="date" name="date_start" id="date" style="width: 400;" <?php if(!$new_ad) echo "value='".$res['date_start']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-6k2t" >Date End</th>
    <th class="tg-6k2t" ><input type="date" name="date_end" id="date" style="width: 400;" <?php if(!$new_ad) echo "value='".$res['date_end']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <td class="tg-6k2t">Description</td>
    <td class="tg-6k2t"><textarea  <?php if(!$edit) echo "disabled" ;?> name="details" id="details" style="height:150; width:400;"><?php if(!$new_ad) echo "value='".$res['details']."'";?></textarea></td>
  </tr>
  <tr>
    <th class="tg-yw4l" >Links</th>
    <th class="tg-yw4l" ><input type="text" name="link" id="link" style="width: 400;" <?php if(!$new_ad) echo "value='".$res['link']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>  
  <tr>
    <th class="tg-6k2t" >Meta</th>
    <th class="tg-6k2t" ><input type="text" name="meta" id="meta" style="width: 400;" <?php if(!$new_ad) echo "value='".$res['meta']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>  

  
  
 <!-- 
  <tr>
    <td class="tg-6k2t"></td>
    <td class="tg-6k2t"><label id="chars" >Characters left: </label></td>
  </tr>
 --> 
<?php if(!$new_ad) { ?>
  <tr>
    <td class="tg-6k2t">Image</td>
    <td class="tg-6k2t"><img src="<?php echo $img_loc; ?>"/></td>
  </tr>
<?php } ?>
<?php if($edit) {?>
  <tr>
    <td class="tg-6k2t"><?php if($new_ad) echo "Add an Image"; else echo "Change Image"; ?></td>
    <td class="tg-6k2t"><input type="file" name="fileToUpload" id="fileToUpload"></td>
  </tr>
<?php } ?>

</table></div>


<?php if($new_ad) { ?>
<br /><br />
<input type="submit" value="Add this advertisment"/>
<?php } else if ($edit) {?>
<br /><br />
<input type="submit" value="Update this advertisment"/>
<?php } ?>
</form>


<?php if(!$new_ad && !$edit) { ?>
<br />
<a href="./ads.php?id=<?php echo $ad_id ?>&edit=true"><button>Edit this advertisment</button</a>
<?php } ?>


<?php /*
<script type="text/javascript">

document.getElementById('chars').innerHTML = "Characters left: " + (650 - content.value.length);
document.getElementById('content').oninput = function () {
document.getElementById('chars').innerHTML = "Characters left: " + (650 - this.value.length);
};
document.getElementById('content').onkeypress = function () {
document.getElementById('chars').innerHTML = "Characters left: " + (650 - this.value.length);
};
</script>
*/?>