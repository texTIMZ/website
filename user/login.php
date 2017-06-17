<?php
//login screen asking for username(email) and password
ob_start();
require_once ('./functions.php');
session_start();
//var_dump($_SESSION);
if (isset($_SESSION['email'])) {
    if(check_login()) header('Location: ./index.php');
    exit();
} 
//session_start();
//check_login();

?>

<h1>User Login Form</h1>
<form method="POST" action="./login.php">
<input type="text" name="email" id="email" placeholder="email"/><br /><br />
<input type="password" name="pass" id="pass" placeholder="password"/> <br /><br />
<input type="submit" name="btn_sub" value="Login"/>

</form>

<?php
require_once ("../config.php");

if (isset($_POST['btn_sub'])) {
    $email = htmlspecialchars($_POST['email']);
    $pass = sha1($_POST['pass'] . "salt");
    $stmt = $conn->prepare("SELECT pk_i_id, count(*) as record FROM t_user where s_email = :email AND s_pass = :pass AND b_active = 1");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':pass', $pass);
    $stmt->execute();
    $uid = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($uid['record'] == 1) {
        //successful login
        $_SESSION['email'] = $email;
        $_SESSION['pass'] = $pass;
        
        //$uid = $stmt->fetch(PDO::FETCH_ASSOC);
        //$fkid = $uid['pk_i_id'];
        $stmt = $conn->prepare("INSERT INTO t_login_record (fk_i_user_id, s_time, s_ip) VALUES (:fkid, :time, :ip)");
        $stmt->bindParam(':fkid', $fkid);
        $stmt->bindParam(':time', $time);
        $stmt->bindParam(':ip', $ip);
        // insert a row
        $fkid = $uid['pk_i_id'];
        $time = date('Y-m-d H:i:s');
        //ip_address
        $ip = getenv('HTTP_CLIENT_IP')?:
 	    getenv('HTTP_X_FORWARDED_FOR')?:
	    getenv('HTTP_X_FORWARDED')?:
	    getenv('HTTP_FORWARDED_FOR')?:
	    getenv('HTTP_FORWARDED')?:
	    getenv('REMOTE_ADDR');
        $stmt->execute();
        
        header('Location: ./index.php');
        
        exit();
    } else {
        //unsuccessful attempt
        session_destroy();
        echo "<br><b>wrong username/password<b>";

    }
    //var_dump($_SESSION);

}
/*
$result = $conn->prepare("SELECT * FROM users WHERE username= :hjhjhjh AND password= :asas");
$result->bindParam(':hjhjhjh', $user);
$result->bindParam(':asas', $password);
$result->execute();
$rows = $result->fetch(PDO::FETCH_NUM);
if($rows > 0) {
header("location: home.php");
}
else{
$errmsg_arr[] = 'Username and Password are not found';
$errflag = true;
}
if($errflag) {
$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
session_write_close();
header("location: index.php");
exit();
}
*/
?>