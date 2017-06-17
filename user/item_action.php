<?php

//backend file to insert news items into db

session_start();
require_once ('./functions.php');
check_login();

if(isset($_SERVER['HTTP_REFERER']) && isset($_POST['headline']) && isset($_POST['content']) && (isset($_POST['is_new']) xor isset($_POST['item_id']) ) ) {
    $ref1 = str_replace("item_action.php", "item.php", "http://".$_SERVER['HTTP_HOST'].''.$_SERVER['PHP_SELF']);
    $ref2 = str_replace("item_action.php", "item.php", "https://".$_SERVER['HTTP_HOST'].''.$_SERVER['PHP_SELF']);
    
    $ref = substr($_SERVER['HTTP_REFERER'],0,strrpos($_SERVER['HTTP_REFERER'],'?'));
    if($ref1 != $ref && $ref2 != $ref && $ref1 != $_SERVER['HTTP_REFERER'] && $ref2 != $_SERVER['HTTP_REFERER']) {
        //echo "$ref1<br>$ref<br>$ref2<br>".$_SERVER['HTTP_REFERER'];
        die("<br>You can't do this1");
    }
    
}
else{
    die("You can't do this2");
}

//var_dump($_FILES);
//image upload
$img_upload = false;
//$root_folder = "/textimz1.1";
if(isset($_FILES["fileToUpload"]["tmp_name"]) && strlen($_FILES["fileToUpload"]["tmp_name"])>0 ) {
    $img_upload = true;    
    $target_dir = "../media/";
    is_dir($target_dir) || mkdir($target_dir);
    $target_file = $target_dir .date('YmdHis'). basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image007 - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
       echo "Sorry, file already exists.";
       $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" && $imageFileType != "JPG" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>$imageFileType file not allowed";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        die("<br>Sorry, your file was not uploaded.");
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
           die ("Sorry, there was an error uploading your file.");
       }
    }

    $img_loc = str_replace("..", "", "http://".$_SERVER['HTTP_HOST'].$target_file);
}

$headline = $_POST['headline'];
$feature = $_POST['featured'];
$content = $_POST['content'];
$link_name = $_POST['link_name'];
$link = $_POST['link'];
$tags = $_POST['tags'];
$slug = slugify($tags);
$allTags = explode("-", $slug);
$category_id = $_POST['Category'];
$count = count($allTags);
$user = get_user_details();
$uid = intval($user['pk_i_id']);
$time = date('Y-m-d H:i:s');

//echo $feature;

$secret = substr(str_shuffle(MD5(microtime()).strtoupper(MD5(microtime()+12))), 0, 16);
//ip_address
$ip = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');



if(isset($_POST['item_id'])) {
    $id = intval($_POST['item_id']);
    $stmt = $conn->prepare("UPDATE t_news_item SET s_headline = :headline , s_content = :content , s_slug = :slug , s_secret = :secret, b_publish = :feature WHERE pk_i_id = :id"); 
    $stmt->bindParam(':headline', $headline);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':secret', $secret);
    $stmt->bindParam(':feature', $feature);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    unset($stmt);

    $stmt = $conn->prepare("UPDATE t_link SET link_name = :link_name, link = :link WHERE pk_i_id = :id"); 
    $stmt->bindParam(':link_name', $link_name);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    unset($stmt);

    $stmt = $conn->prepare("UPDATE t_categories SET fk_i_category_id = :category_id WHERE pk_i_id = :id");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    unset($stmt);

    $stmt = $conn->prepare("UPDATE t_tag SET s_tag_name = :tags WHERE pk_i_id = :id"); 
    $stmt->bindParam(':tags', $tags);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    unset($stmt);

    $stmt = $conn->prepare("UPDATE t_tag_record SET s_tags = :tag WHERE fk_i_tag_id = :id");
    //$stmt->bindParam(':id', $id);
    for ($i=0; $i < $count; $i++) { 
        $stmt->execute(array(
            ':id' => $id,
            ':tag'   => $allTags[$i]
        ));
    }
    unset($stmt);
        
    if($img_upload) {

        $stmt = $conn->prepare("UPDATE t_media SET s_media_type = :media_type, s_source = :img_loc WHERE fk_i_item_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':media_type', $check["mime"]);
        $stmt->bindParam(':img_loc', $img_loc);
        $stmt->execute();
        unset($stmt);
    }
    $stmt = $conn->prepare("INSERT INTO t_edit_record (fk_i_item_id, fk_i_user_id,fk_link_id, s_time, s_ip) VALUES (:fkiid, :fkuid, :fklid, :time, :ip )");
    $stmt->bindParam(':fkiid', $id);
    $stmt->bindParam(':fkuid', $uid);
    $stmt->bindParam(':fklid', $id);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':ip', $ip);        
    $stmt->execute();
    unset($stmt);
    require('../web_services/news_detail.php');
    
    header("Location: ./item.php?id=$id");   
    exit();
} else {
    
    $stmt = $conn->prepare("INSERT INTO t_news_item (fk_i_user_id, s_headline, s_slug, s_content, s_create_time, s_secret, b_publish) VALUES (:fkuid, :headline, :slug, :content, :time, :secret, :feature)");
    $stmt->bindParam(':fkuid', $uid);
    $stmt->bindParam(':headline', $headline);
    $stmt->bindParam(':slug', $slug);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':time', $time);
    $stmt->bindParam(':secret', $secret);
    $stmt->bindParam(':feature', $feature);
    $stmt->execute();
    unset($stmt);

    $stmt = $conn->prepare("SELECT pk_i_id FROM t_news_item ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $nid = intval($res['pk_i_id']);
    $stmt = $conn->prepare("INSERT INTO t_link (fk_i_news_item_id, link_name, link) VALUES (:fknid, :link_name, :link)");
    $stmt->bindParam(':fknid', $nid);
    $stmt->bindParam(':link_name', $link_name);
    $stmt->bindParam(':link', $link);
    $stmt->execute();
    unset($stmt);
    
    $stmt = $conn->prepare("INSERT INTO t_tag (s_tag_name, fk_i_item_id) VALUES (:tags, :fknid)");
    $stmt->bindParam(':tags', $tags);
    $stmt->bindParam(':fknid', $nid);
    $stmt->execute();
    unset($stmt);

     $stmt = $conn->prepare("INSERT INTO t_categories (fk_i_category_id, fk_i_item_id) VALUES (:category_id, :fknid)");
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':fknid', $nid);
    $stmt ->execute();
    unset($stmt);

        $stmt = $conn->prepare("SELECT pk_i_id FROM t_tag ORDER BY pk_i_id DESC LIMIT 1");
    $stmt->execute();
    $res1 = $stmt->fetch(PDO::FETCH_ASSOC);
    $tid = intval($res1['pk_i_id']);
    unset($stmt);

    $stmt = $conn->prepare("INSERT INTO t_tag_record(fk_i_tag_id , s_tags) VALUES(:fktid , :tag);");
    for ($i=0; $i < $count; $i++) { 
        $stmt->execute(array(
            ':fktid' => $tid,
            ':tag'   => $allTags[$i]
        ));
    }

    
    if($img_upload) {
        $stmt = $conn->prepare("SELECT pk_i_id FROM t_news_item ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_id = intval($res['pk_i_id']);
        $stmt = $conn->prepare("INSERT INTO t_media (fk_i_item_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
        $stmt->bindParam(':fkiid', $current_id);
        $stmt->bindParam(':media_type', $check["mime"]);
        $stmt->bindParam(':img_loc', $img_loc);
        $stmt->execute();
        
        
    }
    require('../web_services/news_detail.php');
    
    header("Location: ./items.php");
    exit();
    
}




?>
