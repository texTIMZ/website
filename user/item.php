<?php
//to create, edit, delete(inactivate) a single news item

/**
$_POST[item_id] - 0/false or not set ->new item
$_POST[edit] - 1/true ->view/edit an item
        $_POST[edit] - 0/false or not set ->view
        $_POST[edit] - 1/true ->edit
*/

session_start();

require_once ('./functions.php');

check_login();
if(isset($_GET['id'])) {
    $item_id = intval($_GET['id']);
    $new_item = false;
        
    $res = get_item_details($item_id);
    if($res == null) {
        echo "Invalid ID No";
        exit();
    }
    if(isset($_GET['edit'])) {
        if($_GET['edit']=='true') {
            $edit = true;
            $title = "Edit item #$item_id";
        }
        else {
            $edit = false;
            $title = "View item #$item_id";
        }        
    }
    else {
        $edit = false;
        $title = "View item #$item_id";
    }
        
    $sql = "SELECT s_source FROM t_media where fk_i_item_id = $item_id ORDER BY pk_i_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($img= ($stmt->fetch(PDO::FETCH_ASSOC)));
        $img_loc = $img['s_source'];

    $sql = "SELECT link_name, link FROM t_link where fk_i_news_item_id = $item_id ORDER BY pk_i_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
     $res1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $link_name = $res1['link_name'];
        $link = $res1['link'];

      $sql = "SELECT s_tag_name FROM t_tag where fk_i_item_id = $item_id ORDER BY pk_i_id DESC LIMIT 1";
    $stmt1 = $conn->prepare($sql);
    $stmt1->execute();
     $tags = $stmt1->fetch(PDO::FETCH_ASSOC);
     unset($stmt1);

     
    $stmt1 = $conn->prepare("SELECT * FROM t_categories where fk_i_item_id = $item_id ");
    $stmt1->execute();
    $categories_id = $stmt1->fetch(PDO::FETCH_ASSOC);     
    $cat_id  = $categories_id['fk_i_category_id'];
    unset($stmt1);


     $stmt1= $conn->prepare("SELECT categories_name FROM s_categories WHERE pk_i_id = :cat_id");
     $stmt1->bindParam(':cat_id', $cat_id);
     $stmt1->execute();
     $categories = $stmt1->fetch(PDO::FETCH_ASSOC);
     unset($stmt1);
     }


else {
    $new_item = true;
    $edit = true;
    $title = "Add a new item";
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top;color: black;}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>

<h1><?php echo $title;?></h1><a href="../user">Home</a><br><br>
<form method="POST" action="./item_action.php" enctype="multipart/form-data">
<?php if($new_item) { ?><input type="hidden" name="is_new" id="is_new" value="true"/><?php }?>
<?php if(isset($item_id)) { ?><input type="hidden" name="item_id" id="item_id" value="<?php echo $item_id;?>"/><?php } ?>
<div class="tg-wrap"><table class="tg">
  <tr>
    <th class="tg-yw4l">Headline</th>
    <th class="tg-yw4l" ><input type="text" name="headline" id="headline" style="width: 400;" <?php if(!$new_item) echo "value='".$res['s_headline']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <td class="tg-6k2t">Content</td>
    <td class="tg-6k2t"><textarea  <?php if(!$edit) echo "disabled" ;?> name="content" id="content" style="height:300; width:400;"><?php if(!$new_item) echo addslashes($res['s_content']);?></textarea></td>
  </tr>
  <tr>
    <td class="tg-6k2t"></td>
    <td class="tg-6k2t"><label id="chars" >Characters left: </label></td>
  </tr>
  <tr>
    <th class="tg-yw4l" >Link Name</th>
    <th class="tg-yw4l" ><input type="text" name="link_name" id="link_name" style="width: 400;" <?php if(!$new_item) echo "value='".$res1['link_name']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>  
  <th class="tg-6k2t" >Link</th>
    <th class="tg-6k2t" ><input type="text" name="link" id="link" style="width: 400;" <?php if(!$new_item) echo "value='".$res1['link']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
<tr>
    <th class="tg-yw4l" >Tags</th>
    <th class="tg-yw4l" ><input type="text" name="tags" id="tags" style="width: 400;" <?php if(!$new_item) echo "value='".$tags['s_tag_name']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr> 
  <tr>
    <th class="tg-yw4l" >Featured News</th>
    <th class="tg-yw4l" >
    <span>No :</span><input type="radio" name="featured" id="featured" value="0"  <?php if(!$new_item) echo "value='".$tags['b_publish']."'";?> <?php if(!$edit) echo "disabled" ;?>/>
    
    <span>Yes :</span><input type="radio" name="featured" id="featured" value="1" <?php if(!$new_item) echo "value='".$tags['b_publish']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr> 
  <tr>  
  <th class="tg-6k2t" >Category</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="Category" id="Category" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_categories");
    $query -> execute();
    while ($allcategories = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allcategories['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allcategories['categories_name']; ?></option>
      <?php }
    }
    else if (!$new_item)
      { 
        unset($stmt);
        unset($result);
        $category_id = $res['fk_category_id'];
        $stmt = $conn->prepare("SELECT categories_name FROM s_categories where pk_i_id = $category_id ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $result= ($stmt->fetch(PDO::FETCH_ASSOC));
        $categoryname = $result['categories_name']; ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $allcategories['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $categoryname; ?></textarea>
    <?php  } 
    else { ?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="Category" id="Category" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_categories");
    $query -> execute();
    while ($allcategories = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allcategories['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allcategories['categories_name']; ?></option>
      <?php } }?>
    </select></th>
  </tr>
  </tr>
<tr>  
<?php if(!$new_item) { ?>
  <tr>
    <td class="tg-6k2t">Image</td>
    <td class="tg-6k2t"><img src="<?php echo $img_loc; ?>"/></td>
  </tr>
<?php } ?>
<?php if($edit) {?>
  <tr>
    <td class="tg-6k2t"><?php if($new_item) echo "Add an Image"; else echo "Change Image"; ?></td>
    <td class="tg-6k2t"><input type="file" name="fileToUpload" id="fileToUpload"></td>
  </tr>
<?php } ?>

</table></div>


<?php if($new_item) { ?>
<br /><br />
<input type="submit" value="Add this item"/>
<?php } else if ($edit) {?>
<br /><br />
<input type="submit" value="Update this item"/>
<?php } ?>
</form>


<?php if(!$new_item && !$edit) { ?>
<br />
<a href="./item.php?id=<?php echo $item_id ?>&edit=true"><button>Edit this item</button</a>
<?php } ?>



<script type="text/javascript">

document.getElementById('chars').innerHTML = "Characters left: " + (650 - content.value.length);
document.getElementById('content').oninput = function () {
document.getElementById('chars').innerHTML = "Characters left: " + (650 - this.value.length);
};
document.getElementById('content').onkeypress = function () {
document.getElementById('chars').innerHTML = "Characters left: " + (650 - this.value.length);
};
</script>

