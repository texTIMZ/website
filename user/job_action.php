<?php

//backend file to insert event items into db

session_start();
require_once ('./functions.php');
check_login();
echo "first<br>";

$img_upload = false;
if(isset($_FILES["fileToUpload"]["tmp_name"]) && strlen($_FILES["fileToUpload"]["tmp_name"])>0 ) {
    $img_upload = true;    
    $target_dir = "../media_jobs/";
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

$job = $_POST['job'];
$Company = $_POST['Company'];
$link = $_POST['link'];
$allTags = $_POST['tags'];
//$category_id = $_POST['categories'];
$country_id = $_POST['country'];
$city = $_POST['city'];
$Product = $_POST['Product'];
$venue = $_POST['venue'];
$details = $_POST['details'];
$category_id = $_POST['Category'];
$user = get_user_details();
$uid = intval($user['pk_i_id']);
$time = date('Y-m-d H:i:s');

echo $job." ".$Company." ".$link." ".$allTags." ".$country_id." ".$city." ".$Product_id." ".$venue." ".$details." ".$category_id;

if(isset($_POST['event_id'])) {
    $id = intval($_POST['event_id']);
    $stmt = $conn->prepare("UPDATE t_jobs SET job_name = :job , company_name = :Company, fk_country_id = :country_id, city = :city, venue = :venue, details = :details, product = :Product ,fk_category_id = :category_id, link = :link, tags = :allTags, fk_user_id = :uid  WHERE pk_i_id = :id"); 
    echo "updating";
    $stmt->bindParam(':job', $job);
    $stmt->bindParam(':Company', $Company);
    $stmt->bindParam(':country_id', $country_id);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':Product', $Product);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':venue', $venue);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':allTags', $allTags);
    $stmt->bindParam(':uid', $uid);
    $stmt->bindParam(':id', $id);
     
    
    
    $stmt->execute();
    unset($stmt);

    
    if($img_upload) {
        
        $stmt = $conn->prepare("INSERT INTO t_media_jobs (fk_i_item_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
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
    
    $stmt = $conn->prepare("INSERT INTO t_jobs (job_name, company_name, fk_country_id, city, venue, details, product, fk_category_id, link, tags, fk_user_id) VALUES (:job, :Company, :country_id, :city ,:venue, :details,:Product, :category_id, :link, :allTags,:uid)");
    echo "inserting";
    $stmt->bindParam(':job', $job);
    $stmt->bindParam(':Company', $Company);
    $stmt->bindParam(':country_id', $country_id);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':venue', $venue);
    $stmt->bindParam(':details', $details);
    $stmt->bindParam(':Product', $Product);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->bindParam(':link', $link);
    $stmt->bindParam(':allTags', $allTags);
    $stmt->bindParam(':uid', $uid);
    $stmt->execute();
    unset($stmt);
    echo "<br>img";
    if($img_upload) {
        $stmt = $conn->prepare("SELECT pk_i_id FROM t_jobs ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_id = intval($res['pk_i_id']);
        $stmt = $conn->prepare("INSERT INTO t_media_jobs (fk_job_id, s_media_type, s_source) VALUES (:fkiid, :media_type, :img_loc )");
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