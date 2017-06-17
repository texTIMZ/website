<?php $count = 1; 
$n_articles = 0;

?>
<!DOCTYPE html>
<html>
<head>
	<title>TEXtimz Category</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
	<style type="text/css">
		h1
		{
			color: blue;
		}
		button
		{
			width: auto;
		    height: 50px;
		    background-color: rgba(0, 0, 255, 0.25);
		    border: 2px rgba(0, 0, 255, 0.11) solid;
		    border-radius: 5px;
		    cursor: pointer;

		}
		option
		{
			width: auto;
			height: 30px;
		}
		table
		{
			background-color: rgba(255, 0, 0, 0.15);
			border: 1px solid black;
			margin-top: 10px;
			margin-left: 30%;
		}
		td 
		{
			border: 1.5px solid black;
		}
		th
		{
			border: 1.5px solid black;
		}

	</style>
</head>
<body>
<h1>CATEGORY</h1>
<button id="addCat">Add New Category</button><br><br>
<button id="view">View all Categories</button><br><br>
<a href="../user">Home</a>
</body>
<script type="text/javascript">
	$('#addCat').click(function() {
		var addCat;
		addCat= prompt('Add Category');
		//alert(addCat);
		window.location.href = "category.php?id=" + addCat;
	});

	$('#view').click(function(){
	    		window.location.href = "category.php?mode=view";
	    	});
</script>
</html>
<?php 
include "../config.php";
	if (isset($_GET['id'])) {
		//echo $_GET['id']." added"; 
		$category = $_GET['id'];
		$stmt = $conn->prepare(" INSERT INTO s_categories(categories_name) VALUES(:category)");
		$stmt->bindParam(':category', $category);        
		$stmt->execute();
		unset($stmt);
	    echo $category." added"; 
	}
	if (isset($_GET['mode'])) 
	    {
	    	$mode = $_GET['mode'];
	    	//echo $mode;
	    
		  	if ($mode == "view") 
			 {?>
			     <table class="table">
				 	<tr>
				    <th class="odd" >Category <br>Number</th>
				    <th class="odd" >Category Name</th>
				    <th class="odd" >Number Of <br>Articles</th>
				    <th class="odd" >Delete</th>
				    <th class="odd" >Edit</th>
				  </tr>
				  <?php
			       $stmt1 = $conn->prepare('SELECT * FROM s_categories order by pk_i_id asc ');
			       $stmt1->execute();
			       while($view_item = $stmt1 -> fetch(PDO::FETCH_ASSOC)){?>
				   <tr>
				   <td class="even"><?php echo $count++ ?></td>
				   <?php $cat_id = $view_item['pk_i_id'];?>
				    
				    <td class="even"><?php echo $view_item['categories_name'];?></td>
				    <?php  	$stmt2 = $conn->prepare('SELECT count(pk_i_id) FROM t_categories WHERE fk_i_category_id = :cat_id');
				    		$stmt2->bindParam(':cat_id', $cat_id);        
				    		$stmt2->execute();
				    		$countArticles = $stmt2->fetch(PDO::FETCH_ASSOC);
				    		$countcat = $countArticles['count(pk_i_id)'];
				    		 ?>
				    <td class="even"><?php echo $countcat; ?></td>
				    <td class="even"><a href="Category.php?delete=<?php echo $cat_id; ?>" ><button class ="delete" id ='<?php echo $cat_id ?>'>Delete</button></a></td>
				    <td class="even"><a href="Category.php?edit=<?php echo $cat_id; ?>" ><button class ="edit" id ='<?php echo $cat_id ?>'>Edit</button></a></td>
				   </tr>
				   <?php } ?>
				 </table>

			    <?php }
			}
 ?>
 <?php 
if (isset($_GET['delete'])) 
{
	//echo "<script type='text/javascript'>alert('DELETED');</script>";

	$category_delete = $_GET['delete'];
	echo $category_delete;
	$query   = $conn->prepare("SELECT categories_name from s_categories WHERE `pk_i_id` = :category_delete");
    $query->bindParam(':category_delete', $category_delete);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $category_n = $result['categories_name'];

    echo ". ".$category_n." DELETED";

	$stmt = $conn->prepare("DELETE FROM s_categories WHERE `pk_i_id` = :category_delete");
	$stmt->bindParam('category_delete', $category_delete);
    $stmt->execute();
	unset($stmt);

}
if (isset($_GET['edit']))
{
	$category_edit = $_GET['edit'];
	//echo "<script type='text/javascript'>alert('EDITED');</script>";

	//echo $category_edit;
	$query   = $conn->prepare("SELECT categories_name from s_categories WHERE `pk_i_id` = :category_edit");
    $query->bindParam('category_edit', $category_edit);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $category_n = $result['categories_name'];?>
    <form method= "POST">
    <input type='text' name='category_to_edit' id = "category_edit" value = '<?php echo $category_n ?>'>
    </form>
   	<?php
    if (isset($_POST['category_to_edit'])) 
    {
    	$category_to_edit = $_POST['category_to_edit'];
    	echo $category_edit.".  ".$category_to_edit."  EDITED";
    	$stmt = $conn->prepare("UPDATE s_categories SET categories_name = :category_to_edit  WHERE `pk_i_id` = :category_edit");
    	$stmt->bindParam('category_edit', $category_edit);
    	$stmt->bindParam('category_to_edit', $category_to_edit);
    	$stmt->execute(); 
    	unset($stmt);
    	echo "<script type='text/javascript'>  	$('#category_edit').hide();  </script>";
    }

}
  ?>
  
 
<?php                               

	if (isset($_GET['addCatNews']))
	{
		$stmt = $conn->prepare('SELECT * FROM s_categories order by pk_i_id asc ');
		$stmt->execute();

		$stmt1 = $conn->prepare('SELECT pk_i_id FROM t_news_item order by pk_i_id asc ');
		$stmt1->execute();?>

	<form method="post">
		<select name="categories_name">
		<?php while($view_item = $stmt -> fetch(PDO::FETCH_ASSOC)){
		 $cat_id = $view_item['pk_i_id'];
		 $cat_name =  $view_item['categories_name'];
		?>
    		<option value="<?php echo  $cat_id ?>"><?php echo $cat_name ?></option>
    	<?php } ?>
  		</select>
  		<select name="news_id">
		<?php while($news_item = $stmt1 -> fetch(PDO::FETCH_ASSOC)){
		 $news_id = $news_item['pk_i_id'];
		?>
    		<option value="<?php echo $news_id ?>"><?php echo $news_id ?></option>
    	<?php } ?>
  		</select>
  		<input type="submit" name="submit">
	</form>	
	<?php 
		if (isset($_POST['categories_name'])) {
			$val = $_POST['news_id'];
			$newsId = $_POST['categories_name'];
			unset($stmt);
			$stmt = $conn->prepare("INSERT INTO t_categories(fk_i_category_id,fk_i_item_id) VALUES(:val, :newsId)");
			$stmt->bindParam('val', $val);
			$stmt->bindParam('newsId', $newsId);
			$stmt->execute(); 
			//echo "string";
		}
	 ?>
	
	<?php }
 ?>