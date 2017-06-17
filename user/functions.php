<?php
//session_start();
require_once ("../config.php");



function check_login() {    
    require ("../config.php");
    if(isset($_SESSION['email']) && isset($_SESSION['email']) ) {
        $email = htmlspecialchars($_SESSION['email']);
        $pass = htmlspecialchars($_SESSION['pass']);
        $stmt = $conn->prepare("SELECT pk_i_id FROM t_user where s_email = :email AND s_pass = :pass AND b_active = 1"); 
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $pass);
        $stmt->execute();
        if($stmt->rowCount()==1) {
            //successful login
            //header('Location: ./index.php');
            echo "<a href = './index.php'>Home</a> &emsp;";
            echo "<a href = './logout.php'>Logout</a> <br /><br />";
            
            return true;        
        }else {
            //unsuccessful attempt by session - possible hacking attempt
            //echo("ERROR#1");
            session_destroy();
            header('Location: ./login.php');
            exit();
        }
    
    }
    else {
            session_destroy();
            header('Location: ./login.php');
            exit();
        }
}

function get_user_details() {
    if(isset($_SESSION['email']) && isset($_SESSION['email']) ) {
        require ("../config.php");
        $email = htmlspecialchars($_SESSION['email']);
        $pass = htmlspecialchars($_SESSION['pass']);
        $stmt = $conn->prepare("SELECT * FROM t_user where s_email = :email AND s_pass = :pass AND b_active = 1"); 
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':pass', $pass);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }
    
}

function get_item_details($id) {
    if(isset($id)) {
        require ("../config.php");
        $id = intval($id);
        $stmt = $conn->prepare("SELECT COUNT(*) AS record FROM t_news_item WHERE pk_i_id = :id and b_active = 1"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['record'] != 1 )
            return null;
        $stmt = $conn->prepare("SELECT * FROM t_news_item WHERE pk_i_id = :id and b_active = 1"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }
    
}
    
function get_event_details($id) {
    if(isset($id)) {
        require ("../config.php");
        $id = intval($id);
        $stmt = $conn->prepare("SELECT COUNT(*) AS record FROM t_events WHERE pk_i_id = :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['record'] != 1 )
            return null;
        $stmt = $conn->prepare("SELECT * FROM t_events WHERE pk_i_id = :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }    
    
}
function get_job_details($id) {
    if(isset($id)) {
        require ("../config.php");
        $id = intval($id);
        $stmt = $conn->prepare("SELECT COUNT(*) AS record FROM t_jobs WHERE pk_i_id = :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['record'] != 1 )
            return null;
        $stmt = $conn->prepare("SELECT * FROM t_jobs WHERE pk_i_id = :id"); 
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
        
    }    
    
}
function slugify($text)
{
   // echo "<script type='text/javascript'>alert('message');</script>";
  // replace non letter or digits by -
  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

  // transliterate
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

  // remove unwanted characters
  $text = preg_replace('~[^-\w]+~', '', $text);

  // trim
  $text = trim($text, '-');

  // remove duplicate -
  $text = preg_replace('~-+~', '-', $text);

  // lowercase
  $text = strtolower($text);

  if (empty($text)) {
    return 'n-a';
  }

  return $text;
}

?>