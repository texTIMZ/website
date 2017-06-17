<?php

//backend file to insert event items into db

session_start();
require_once ('./functions.php');
check_login();

//echo "first<br>";
//var_dump($_FILES);
//image upload
$img_upload = false;
if(isset($_FILES["fileToUpload"]["tmp_name"]) && strlen($_FILES["fileToUpload"]["tmp_name"])>0 ) {
    $img_upload = true;    
    $target_dir = "../media_events/";
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

$event = $_POST['event'];
$date_start = $_POST['date_start'];
$date_end = $_POST['date_end'];
$link = $_POST['link'];
$Tags = $_POST['tags'];
$active = $_POST['active'];
$Publish = $_POST['Publish'];
$media_link = $_POST['media_link'];
$allTags = slugify($_POST['tags']);
//$category_id = $_POST['categories'];
$country_id = $_POST['country'];
$city = $_POST['city'];
$Product = $_POST['Product'];
$attendee = $_POST['attendee'];
$venue = $_POST['venue'];
$details = $_POST['details'];
$user = get_user_details();
$uid = intval($user['pk_i_id']);
$time = date('Y-m-d H:i:s');
$category_id = $_POST['Category'];

echo $event." ".$date_start." ".$date_end." ".$time_start." ".$time_end." ".$link." ".$allTags." ".$country_id." ".$city_id." ".$Product ." ".$attendee." ".$venue." ".$details." ".$category_id;

if(isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("UPDATE t_events SET event_name = :event , date_start = :date_start ,date_end = :date_end, fk_country_id = :country_id, city = :city, venue = :venue, details = :details, fk_product = :Product ,fk_category_id = :category_id, fk_attendee = :attendee,  link = :link, media_link = :media_link, tags = :allTags, fk_user_id = :uid, b_active = :active, b_publish = :Publish WHERE pk_i_id = :id"); 
    echo "updating";
    $stmt->bindParam(':event', $event);
    $stmt->bindParam(':date_start', $date_start);
    $stmt->bindParam(':date_end', $date_end);
    $stmt->bindParam(':country_id', $country_id);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':Product', $Product);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':attendee', $attendee);
    $stmt->bindParam(':venue', $venue);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':media_link', $media_link);
    $stmt->bindParam(':allTags', $allTags);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':id', $_POST['id']);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':Publish', $Publish);
     
    echo "Updated";
    
    $stmt->execute();
    unset($stmt);

    
    if($img_upload) {
        
        $stmt = $conn->prepare("INSERT INTO t_media_events (fk_i_item_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
        $stmt->bindParam(':fkiid', $id);
        $stmt->bindParam(':media_type', $check["mime"]);
        $stmt->bindParam(':img_loc', $img_loc);
        $stmt->execute();
        
        
    }
    require('../web_services/news_detail.php');
    
    header("Location: ./event.php?id=$id");
       
    exit();
} else {
    //$slug = "item-".str_shuffle($secret);
    
    $stmt = $conn->prepare("INSERT INTO t_events (event_name, date_start,date_end, fk_country_id, city, venue, details, product, fk_category_id, attendee,link,media_link, tags, fk_user_id, b_active , b_publish) VALUES (:event, :date_start, :date_end, :country_id, :city,:venue, :details,:Product, :category_id, :attendee ,:link, :media_link, :allTags, :uid, :active, :Publish)");
    echo "inserting";
    $stmt->bindParam(':event', $event);
    $stmt->bindParam(':date_start', $date_start);
    $stmt->bindParam(':date_end', $date_end);
    $stmt->bindParam(':country_id', $country_id);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':venue', $venue);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':Product', $Product);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':attendee', $attendee);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':media_link', $media_link);
    $stmt->bindParam(':allTags', $allTags);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':active', $active);
    $stmt->bindParam(':Publish', $Publish);
    $stmt->execute();
    unset($stmt);
    echo "<br>img";
    if($img_upload) {
        $stmt = $conn->prepare("SELECT pk_i_id FROM t_events ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_id = intval($res['pk_i_id']);
        $stmt = $conn->prepare("INSERT INTO t_media_events (fk_event_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
        $stmt->bindParam(':fkiid', $current_id);
        $stmt->bindParam(':media_type', $check["mime"]);
        $stmt->bindParam(':img_loc', $img_loc);
        $stmt->execute();
        
        
    }
    echo "done";
    require('../web_services/news_detail.php');
    
    header("Location: ./events.php");
    exit();
    
}




?>