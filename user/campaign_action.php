<?php

//backend file to insert ad items into db

session_start();
require_once ('./functions.php');
check_login();
echo "first<br>";

$img_upload = false;
if(isset($_FILES["fileToUpload"]["tmp_name"]) && strlen($_FILES["fileToUpload"]["tmp_name"])>0 ) {
    $img_upload = true;    
    $target_dir = "../media_campaigns/";
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

$Campaign_name = $_POST['Campaign_name'];
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];
$link = $_POST['link'];
$meta = $_POST['meta'];
//$category_id = $_POST['categories'];
$details = $_POST['details'];
$user = get_user_details();
$uid = intval($user['pk_i_id']);
$time = date('Y-m-d H:i:s');

echo $Campaign_name." ".$date_start." ".$date_end." ".$link." ".$meta." ".$details;

if(isset($_POST['ad_id'])) {
    $id = intval($_POST['ad_id']);
    $stmt = $conn->prepare("UPDATE t_ads SET Campaign_name = :Campaign_name , date_start = :date_start ,date_end = :date_end, details = :details,link = :link, meta = :meta, fk_user_id = :uid WHERE pk_i_id = :id"); 
    echo "updating";
    $stmt->bindParam(':Campaign_name', $Campaign_name);
    $stmt->bindParam(':date_start', $date_start);
    $stmt->bindParam(':date_end', $date_end);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':meta', $meta);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':id', $_POST['id']);
     
    
    
    $stmt->execute();
    unset($stmt);

    
    if($img_upload) {
        
        $stmt = $conn->prepare("INSERT INTO t_media_ads (fk_ads_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
        $stmt->bindParam(':fkiid', $id);
        $stmt->bindParam(':media_type', $check["mime"]);
        $stmt->bindParam(':img_loc', $img_loc);
        $stmt->execute();
        
        
    }
    //require('../web_services/news_detail.php');
    
    header("Location: ./ad.php?id=$id");
       
    exit();
} else {
    //$slug = "item-".str_shuffle($secret);
    
    $stmt = $conn->prepare("INSERT INTO t_ads (Campaign_name, date_start,date_end, details,link, meta, fk_user_id) VALUES (:Campaign_name, :date_start, :date_end,:details,:link, :meta, :uid)");
    echo "inserting";
    $stmt->bindParam(':Campaign_name', $Campaign_name);
    $stmt->bindParam(':date_start', $date_start);
    $stmt->bindParam(':date_end', $date_end);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':meta', $meta);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    unset($stmt);
    echo "<br>img";
    if($img_upload) {
        $stmt = $conn->prepare("SELECT pk_i_id FROM t_ads ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_id = intval($res['pk_i_id']);
        $stmt = $conn->prepare("INSERT INTO t_media_ads (fk_ads_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
        $stmt->bindParam(':fkiid', $current_id);
        $stmt->bindParam(':media_type', $check["mime"]);
        $stmt->bindParam(':img_loc', $img_loc);
        $stmt->execute();
        
        
    }
    echo "done";
    //require('../web_services/news_detail.php');
    
    header("Location: ./ads.php");
    exit();
    
}




?>