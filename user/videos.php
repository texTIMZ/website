<?php

session_start();
require_once ('./functions.php');
check_login();
$user = get_user_details();
$uid = intval($user['pk_i_id']);
//echo $uid;


?>

<form method="POST">
	<label>Link Name</label>
	<input type="text" name="link_name"><br>
	<label>Link</label>
	<input type="text" name="link"><br>
	<input type="submit" name="submit">
</form>
<a href='videos.php?mode=view'>View all</a><br>

<?php
if (isset($_POST['link_name']) && isset($_POST['link'])) {
	$link_name = $_POST['link_name'];
	$link = $_POST['link'];

	$stmt = $conn->prepare("INSERT INTO t_videos(video_name, link ,fk_user_id) VALUES (:link_name, :link , :uid)");
	$stmt-> bindparam(':link_name', $link_name);
	$stmt-> bindparam(':link', $link);
	$stmt-> bindparam(':uid', $uid);
    $stmt->execute();
    echo "Link ".$link_name." Added";
    echo "<a href='videos.php?mode=view'>View all</a><br>";

	}
if ($_GET['mode']=="view") {

	$query = $conn->prepare("SELECT * FROM t_videos");
	$query->execute();
	

	?>
	<table>
		<tr>
		<th>Number</th>		
		<th>Link Name</th>
		<th>Link</th>
		<th>Delete</th>
		<th>Edit</th>
		</tr>

		<?php $i=0;
		while ($result = $query->fetch(PDO::FETCH_ASSOC)) {?>
		<tr>
			<th><?php echo ++$i;	 ?></th>
			<th><?php echo $result['video_name'];	 ?></th>
			<th><?php echo $result['link'];	 ?></th>
			<th><a href='videos.php?mode=Delete&&id=<?php echo $result['pk_i_id']; ?>'><button>Delete</button></a></th>
			<th><a href='videos.php?mode=edit&&id=<?php echo $result['pk_i_id']; ?>'><button>Edit</button></a></th>
		</tr>
		
		<?php } ?>
	</table>


<?php }
if ($_GET['mode']=='Delete') {
	echo "Delete";
	$id = $_GET['id'];
	unset($stmt);
	unset($query);
	$query= $conn->prepare("SELECT video_name FROM t_videos where pk_i_id = :id");
	$query->bindparam(':id', $id);
	$query->execute();
	unset($result);
	$result = $query->fetch(PDO::FETCH_ASSOC);
	echo $result['video_name'];
	$stmt= $conn->prepare("DELETE FROM t_videos where pk_i_id = :id");
	$stmt->bindparam(':id', $id);
	$stmt->execute();
	echo "<a href='videos.php?mode=view'>View all</a><br>";
}
if ($_GET['mode']=='edit') 
{

	$id = $_GET['id'];
	unset($stmt);
	unset($query);
	$query= $conn->prepare("SELECT video_name, link FROM t_videos where pk_i_id = :id");
	$query->bindparam(':id', $id);
	$query->execute();
	unset($result);
	$result = $query->fetch(PDO::FETCH_ASSOC);
	echo "<br>What Do You wish to change:";
	echo "<br><a href = 'videos.php?edit=Video_Name&&id=$id'>Video Name</a><br>";
	echo "<a href = 'videos.php?edit=Link&&id=$id'>link</a>";
}
if ($_GET['edit']=='Video_Name') 
	{?>
    <form method="POST">
	<label>Video Name:</label>
		<input type="text" name="vname" ><br>
		<input type="submit" name="submit">
	</form>

<?php
echo $_POST['vname'];
$vname = $_POST['vname'];
$id = $_GET['id'];
	$stmt= $conn->prepare("UPDATE t_videos SET video_name = :vname where pk_i_id = :id");
	$stmt->bindparam(':vname', $vname);
	$stmt->bindparam(':id', $id);
	$stmt->execute(); }
if ($_GET['edit']=='Link') 
	{?>
    <form method="POST">
	<label>link:</label>
		<input type="text" name="link" ><br>
		<input type="submit" name="submit">
	</form>

<?php
echo $_POST['link'];
$link = $_POST['link'];
$id = $_GET['id'];
	$stmt= $conn->prepare("UPDATE t_videos SET link = :link where pk_i_id = :id");
	$stmt->bindparam(':link', $link);
	$stmt->bindparam(':id', $id);
	$stmt->execute(); }
	?>
	