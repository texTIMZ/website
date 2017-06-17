<?php
include "../config.php";
$count=1;
if(isset($_GET['w']))
{
	$i = $_GET['w'];
	if ($i=="country") 
	{?>
	<h1>Country</h1>

	<a href="extras.php?mode=add_coun"><button id="addCat">Add New Country</button></a><br><br>
	<a href="extras.php?mode=view_coun"><button id="view">View all Country</button></a><br><br>
	<a href="../user">Home</a>
	<?php 
	}
	elseif ($i=="city") 
	{?>
	<h1>City</h1>

	<a href="extras.php?mode=add_city"><button id="addCat">Add New City</button></a><br><br>
	<a href="extras.php?mode=view_city"><button id="view">View all City</button></a><br><br>
	<a href="../user">Home</a>
	<?php 
	}
	elseif ($i=="product") 
	{?>
	<h1>Product</h1>

	<a href="extras.php?mode=add_product"><button id="addCat">Add New product</button></a><br><br>
	<a href="extras.php?mode=view_product"><button id="view">View all product</button></a><br><br>
	<a href="../user">Home</a>
	<?php 
	}
	elseif ($i=="attendee") 
	{?>
	<h1>Attendee</h1>

	<a href="extras.php?mode=add_attendee"><button id="addCat">Add New attendee</button></a><br><br>
	<a href="extras.php?mode=view_attendee"><button id="view">View all attendee</button></a><br><br>
	<a href="../user">Home</a>
	<?php 
	}
}

if (isset($_GET['mode'])) 
{
	if ($_GET['mode']=="view_coun") 
	{
		$stmt = $conn->prepare("SELECT * FROM s_country");
		$stmt->execute(); 
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC))
            {
            	$countriesid[] = $result['pk_i_id'];
                $countries[] = $result['country_name'];
            }
    $totalcountries = count($countries);?>
    		<table class="table">
				 	<tr>
				    <th class="odd" >Country <br>Number</th>
				    <th class="odd" >Country Name</th>
				    <th class="odd" >Delete</th>
				    <th class="odd" >Edit</th>
				  </tr>
				  <?php
			       for ($i=0; $i < $totalcountries ; $i++) 
			       	{ ?>
				   <tr>
				   <td class="even"><?php echo $i+1 ?></td>
				    <td class="even"><?php echo $countries[$i];?></td>
				    <td class="even"><a href="extras.php?countrydelete=<?php echo $countriesid[$i]; ?>" ><button class ="delete" id ='<?php echo $countriesid[$i] ?>'>Delete</button></a></td>
				    <td class="even"><a href="extras.php?countryedit=<?php echo $countriesid[$i]; ?>" ><button class ="edit" id ='<?php echo $countriesid[$i] ?>'>Edit</button></a></td>
				   </tr>
				   <?php } ?>
			 </table>
				 <a href="extras.php?mode=add_coun">add Country</a>
	<?php }
	unset($stmt);
	if ($_GET['mode']=="add_coun") 
	{?>
	<form method="post">
	Add Country :
	<input type='text' name='country'>	
	<br> <button>Submit </button>
	</form>
	<?php 
	}
	if (isset($_POST['country'])) 
	{
		$Country_name = $_POST['country'];
		$stmt = $conn->prepare("INSERT INTO s_country(country_name) VALUES(:Country_name)");
		$stmt->bindParam('Country_name', $Country_name);
		$stmt->execute(); 
		echo $Country_name." Added";
		echo "<br><a href='extras.php?mode=view_coun'> View</a>";
				
	}
}
if (isset($_GET['countrydelete'])) 
{
	//echo "<script type='text/javascript'>alert('DELETED');</script>";
unset($query);
unset($stmt);
	$country_delete = $_GET['countrydelete'];
	echo $country_delete;
	$query = $conn->prepare("SELECT country_name FROM s_country WHERE `pk_i_id` = :country_delete");
    $query->bindParam(':country_delete', $country_delete);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $country_n = $result['country_name'];
    echo $country_n;
    echo "delete<br>";

	$stmt = $conn->prepare("DELETE FROM s_country WHERE `pk_i_id` = :country_delete");
	$stmt->bindParam(':country_delete', $country_delete);
    $stmt->execute();
	unset($stmt);
	echo ". ".$country_n." DELETED";

}
if (isset($_GET['countryedit']))
{
	$country_edit = $_GET['countryedit'];
	//echo "<script type='text/javascript'>alert('EDITED');</script>";

	//echo $country_n;
	$query   = $conn->prepare("SELECT country_name from s_country WHERE `pk_i_id` = :country_edit");
    $query->bindParam('country_edit', $country_edit);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $country_n = $result['country_name'];?>
    <form method= "POST">
    <input type='text' name='country_to_edit' id = "country_edit" value = '<?php echo $country_n ?>'>
    </form>
   	<?php
    if (isset($_POST['country_to_edit'])) 
    {
    	$country_to_edit = $_POST['country_to_edit'];
    	echo $country_edit.".  ".$country_to_edit."  EDITED";
    	$stmt = $conn->prepare("UPDATE s_country SET country_name = :country_to_edit  WHERE `pk_i_id` = :country_edit");
    	$stmt->bindParam('country_edit', $country_edit);
    	$stmt->bindParam('country_to_edit', $country_to_edit);
    	$stmt->execute(); 
    	unset($stmt);
    	echo "<script type='text/javascript'>  	$('#country_edit').hide();  </script>";
    }

}
  
if (isset($_GET['mode'])) 
{
	if ($_GET['mode']=="view_city") 
	{
		$stmt = $conn->prepare("SELECT * FROM s_city");
		$stmt->execute(); 
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $Cities[]         = $result['city_name'];
                $Citiesid[]         = $result['pk_i_id'];
                $Country_id[]  = $result['fk_country_id'];
            }
            $totalcities = count($Cities);?>
   			 <table class="table">
				 	<tr>
				    <th class="odd" >City <br>Number</th>
				    <th class="odd" >City Name</th>
				    <th class="odd">Country Name</th>
				    <th class="odd" >Delete</th>
				    <th class="odd" >Edit</th>
				  </tr>
				  <?php
			       for ($i=0; $i < $totalcities ; $i++) {
			       	$countriesid = $Country_id[$i];
			       	$stmt1 = $conn->prepare("SELECT * FROM s_country WHERE pk_i_id = $countriesid ");
					$stmt1->execute();
					$rCountry = $stmt1->fetch(PDO::FETCH_ASSOC);
					$country_assoc = $rCountry['country_name'];
			       	?>

				   <tr>
				   <td class="even"><?php echo $i+1 ?></td>
				    <td class="even"><?php echo $Cities[$i];?></td>
				    <td class="even"><?php echo $country_assoc?></td>
				    <td class="even"><a href="extras.php?citydelete=<?php echo $Citiesid[$i]; ?>" ><button class ="delete" id ='<?php echo $Citiesid[$i] ?>'>Delete</button></a></td>
				    <td class="even"><a href="extras.php?cityedit=<?php echo $Citiesid[$i]; ?>" ><button class ="edit" id ='<?php echo $Citiesid[$i] ?>'>Edit</button></a></td>
				   </tr>
				   <?php } ?>
				 </table>
				 <a href="extras.php?mode=add_city">add City</a>
	<?php }
	unset($stmt);
	if ($_GET['mode']=="add_city") 
	{?>
	<form method="post">

	Add City :
	<input type='text' name='city'>	<br>
	Country :
    <select name="countryname" id="countryname">
    <?php  $query = $conn->prepare("SELECT * FROM s_country");
    $query -> execute();
    while ($allcountries = $query->fetch(PDO::FETCH_ASSOC)) 
    	{ ?>
      <option value="<?php echo $allcountries['pk_i_id']; ?>"><?php echo $allcountries['country_name']; ?></option>
      <?php } ?>
    </select>
	<br> <button>Submit </button>

	</form>
	<?php 
	}
	if (isset($_POST['city'])) 
	{
		$City_name = $_POST['city'];
		$fk_country_id = $_POST['countryname'];
		$stmt = $conn->prepare("INSERT INTO s_city(city_name,fk_country_id) VALUES(:City_name,:fk_country_id)");
		$stmt->bindParam('City_name', $City_name);
		$stmt->bindParam('fk_country_id', $fk_country_id);
		$stmt->execute(); 
		echo $City_name." Added";
		echo "<br><a href='extras.php?mode=view_city'> View</a>";
				
	}
}
if (isset($_GET['citydelete'])) 
{
	//echo "<script type='text/javascript'>alert('DELETED');</script>";
unset($query);
unset($stmt);
	$city_delete = $_GET['citydelete'];
	echo $city_delete;
	$query = $conn->prepare("SELECT city_name FROM s_city WHERE `pk_i_id` = :city_delete");
    $query->bindParam(':city_delete', $city_delete);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $city_n = $result['city_name'];
    echo $city_n;
    echo "delete<br>";

	$stmt = $conn->prepare("DELETE FROM s_city WHERE `pk_i_id` = :city_delete");
	$stmt->bindParam(':city_delete', $city_delete);
    $stmt->execute();
	unset($stmt);
	echo ". ".$city_n." DELETED";

}
if (isset($_GET['cityedit']))
{
	$city_edit = $_GET['cityedit'];
	//echo "<script type='text/javascript'>alert('EDITED');</script>";

	//echo $country_n;
	$query   = $conn->prepare("SELECT city_name from s_city WHERE `pk_i_id` = :city_edit");
    $query->bindParam('city_edit', $city_edit);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $city_n = $result['city_name'];?>
    <form method= "POST">
    <input type='text' name='city_to_edit' id = "city_edit" value = '<?php echo $city_n ?>'>
    </form>
   	<?php
    if (isset($_POST['city_to_edit'])) 
    {
    	$city_to_edit = $_POST['city_to_edit'];
    	echo $city_edit.".  ".$city_to_edit."  EDITED";
    	$stmt = $conn->prepare("UPDATE s_city SET city_name = :city_to_edit  WHERE `pk_i_id` = :city_edit");
    	$stmt->bindParam('city_edit', $city_edit);
    	$stmt->bindParam('city_to_edit', $city_to_edit);
    	$stmt->execute(); 
    	unset($stmt);
    	echo "<script type='text/javascript'>  	$('#city_edit').hide();  </script>";
    }

}


if (isset($_GET['mode'])) 
{unset($result);
	if ($_GET['mode']=="view_product") 
	{
		$stmt = $conn->prepare("SELECT * FROM s_product");
		$stmt->execute(); 
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                    $products[]         = $result['product_name'];
                    $productsid[]         = $result['pk_i_id'];
            }
    $totalproducts = count($products);?>
    <table class="table">
				 	<tr>
				    <th class="odd" >product <br>Number</th>
				    <th class="odd" >products Name</th>
				    <th class="odd" >Delete</th>
				    <th class="odd" >Edit</th>
				  </tr>
				  <?php
			       for ($i=0; $i < $totalproducts ; $i++) { ?>
				   <tr>
				   <td class="even"><?php echo $i+1 ;?></td>
				    <td class="even"><?php echo $products[$i];?></td>
				    <td class="even"><a href="extras.php?productdelete=<?php echo $productsid[$i]; ?>" ><button class ="delete" id ='<?php echo $productsid[$i] ?>'>Delete</button></a></td>
				    <td class="even"><a href="extras.php?productsedit=<?php echo $productsid[$i]; ?>" ><button class ="edit" id ='<?php echo $productsid[$i] ?>'>Edit</button></a></td>
				   </tr>
				   <?php } ?>
				 </table>
				 <a href="extras.php?mode=add_coun">add product</a>
	<?php }
	unset($stmt);
	if ($_GET['mode']=="add_product") 
	{?>
	<form method="post">
	Add Product :
	<input type='text' name='product'>	
	<br> <button>Submit </button>
	</form>
	<?php 
	}
	if (isset($_POST['product'])) 
	{
		$product_name = $_POST['product'];
		$stmt = $conn->prepare("INSERT INTO s_product(product_name) VALUES(:product_name)");
		$stmt->bindParam('product_name', $product_name);
		$stmt->execute(); 
		echo $product_name." Added";
		echo "<br><a href='extras.php?mode=view_product'> View</a>";
				
	}
}

if (isset($_GET['productdelete'])) 
{
	//echo "<script type='text/javascript'>alert('DELETED');</script>";
unset($query);
unset($stmt);
	$product_delete = $_GET['productdelete'];
	echo $product_delete;
	$query = $conn->prepare("SELECT product_name FROM s_product WHERE `pk_i_id` = :product_delete");
    $query->bindParam(':product_delete', $product_delete);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $product_n = $result['product_name'];
    echo $product_n;
    echo "delete<br>";

	$stmt = $conn->prepare("DELETE FROM s_product WHERE `pk_i_id` = :product_delete");
	$stmt->bindParam(':product_delete', $product_delete);
    $stmt->execute();
	unset($stmt);
	echo ". ".$product_n." DELETED";

}
if (isset($_GET['productsedit']))
{
	$product_edit = $_GET['productsedit'];
	//echo "<script type='text/javascript'>alert('EDITED');</script>";

	//echo $country_n;
	$query   = $conn->prepare("SELECT product_name from s_product WHERE `pk_i_id` = :product_edit");
    $query->bindParam('product_edit', $product_edit);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $product_n = $result['product_name'];?>
    <form method= "POST">
    <input type='text' name='product_to_edit' id = "product_edit" value = '<?php echo $product_n ?>'>
    </form>
   	<?php
    if (isset($_POST['product_to_edit'])) 
    {
    	$product_to_edit = $_POST['product_to_edit'];
    	echo $product_edit.".  ".$product_to_edit."  EDITED";
    	$stmt = $conn->prepare("UPDATE s_product SET product_name = :product_to_edit  WHERE `pk_i_id` = :product_edit");
    	$stmt->bindParam('product_edit', $product_edit);
    	$stmt->bindParam('product_to_edit', $product_to_edit);
    	$stmt->execute(); 
    	unset($stmt);
    	echo "<script type='text/javascript'>  	$('#product_edit').hide();  </script>";
    }

}


if (isset($_GET['mode'])) 
{unset($result);
	if ($_GET['mode']=="view_attendee") 
	{
		$stmt = $conn->prepare("SELECT * FROM s_attendee");
		$stmt->execute(); 
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                    $attendees[]         = $result['attendee'];
                    $attendeesid[]         = $result['pk_i_id'];
            }
    $totalattendees = count($attendees);?>
    <table class="table">
				 	<tr>
				    <th class="odd" >attendee <br>Number</th>
				    <th class="odd" >attendee Name</th>
				    <th class="odd" >Delete</th>
				    <th class="odd" >Edit</th>
				  </tr>
				  <?php
			       for ($i=0; $i < $totalattendees ; $i++) { ?>
				   <tr>
				   <td class="even"><?php echo $i+1 ;?></td>
				    <td class="even"><?php echo $attendees[$i];?></td>
				    <td class="even"><a href="extras.php?attendeedelete=<?php echo $attendeesid[$i]; ?>" ><button class ="delete" id ='<?php echo $attendeesid[$i] ?>'>Delete</button></a></td>
				    <td class="even"><a href="extras.php?attendeesedit=<?php echo $attendeesid[$i]; ?>" ><button class ="edit" id ='<?php echo $attendeesid[$i] ?>'>Edit</button></a></td>
				   </tr>
				   <?php } ?>
				 </table>
				 <a href="extras.php?mode=add_attendee">add attendee</a>
	<?php }
	unset($stmt);
	if ($_GET['mode']=="add_attendee") 
	{?>
	<form method="post">
	Add attendee :
	<input type='text' name='attendee'>	
	<br> <button>Submit </button>
	</form>
	<?php 
	}
	if (isset($_POST['attendee'])) 
	{
		$attendee = $_POST['attendee'];
		$stmt = $conn->prepare("INSERT INTO s_attendee(attendee) VALUES(:attendee)");
		$stmt->bindParam('attendee', $attendee);
		$stmt->execute(); 
		echo $attendee." Added";
		echo "<br><a href='extras.php?mode=view_attendee'>View</a>";
				
	}
}

if (isset($_GET['attendeedelete'])) 
{
	//echo "<script type='text/javascript'>alert('DELETED');</script>";
unset($query);
unset($stmt);
	$attendees_delete = $_GET['attendeedelete'];
	echo $attendees_delete;
	$query = $conn->prepare("SELECT attendee FROM s_attendee WHERE `pk_i_id` = :attendees_delete");
    $query->bindParam(':attendees_delete', $attendees_delete);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $attendee_n = $result['attendee'];
    echo $attendee_n;
    echo "delete<br>";

	$stmt = $conn->prepare("DELETE FROM s_attendee WHERE `pk_i_id` = :attendees_delete");
	$stmt->bindParam(':attendees_delete', $attendees_delete);
    $stmt->execute();
	unset($stmt);
	echo ". ".$attendee_n." DELETED";

}
if (isset($_GET['attendeesedit']))
{
	$attendee_edit = $_GET['attendeesedit'];
	//echo "<script type='text/javascript'>alert('EDITED');</script>";

	//echo $country_n;
	$query   = $conn->prepare("SELECT attendee from s_attendee WHERE `pk_i_id` = :attendee_edit");
    $query->bindParam('attendee_edit', $attendee_edit);
    $query->execute();
    $result  = $query->fetch(PDO::FETCH_ASSOC);
    $attendee_n = $result['attendee'];?>
    <form method= "POST">
    <input type='text' name='attendee_to_edit' id = "attendee_edit" value = '<?php echo $attendee_n ?>'>
    </form>
   	<?php
    if (isset($_POST['attendee_to_edit'])) 
    {
    	$attendee_to_edit = $_POST['attendee_to_edit'];
    	echo $attendee_edit.".  ".$attendee_to_edit."  EDITED";
    	$stmt = $conn->prepare("UPDATE s_attendee SET attendee = :attendee_to_edit  WHERE `pk_i_id` = :attendee_edit");
    	$stmt->bindParam('attendee_edit', $attendee_edit);
    	$stmt->bindParam('attendee_to_edit', $attendee_to_edit);
    	$stmt->execute(); 
    	unset($stmt);
    	echo "<script type='text/javascript'>  	$('#attendee_edit').hide();  </script>";
    }

}

