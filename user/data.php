<!DOCTYPE html>
<html>
<head>
	<title>menu</title>
</head>
<body>
<?php
if (isset($_GET['i'])) 
{
	$i = $_GET['i'];

	switch ($i) {
		case 1: echo "<a href='./items.php'>View All Items</a>
					<br />
					<a href='./item.php'>Add a new Item</a>
					<br /><br />";
					break;
		case 2:	echo "<a href='./events.php'>View All Events</a>
					<br />
					<a href='./event.php?event=true'>Add a new Event</a>";
					break;
		case 3:	echo "<a href='./jobs.php'>View All Jobs</a>
						<br />
						<a href='./job.php?job=true'>Add a new jobs</a>";
					break;
		case 4:	echo "<a href='./newsweave.php'>Newsweave</a>";
					break;
		case 5:	echo "<a href='./category.php'>Add Category</a><br><br>
					<a href='./extras.php?w=country'>country</a><br><br>
					<a href='./extras.php?w=city'>city</a><br><br>
					<a href='./extras.php?w=product'>product</a><br><br>
					<a href='./extras.php?w=attendee'>attendee</a><br><br>
					<a href='./ads.php'>Advertisment</a><br><br>
					<a href='./videos.php'>Videos</a><br><br>";
					break;
		default:
			echo "error";
			break;
	}
}?>

</body>
</html>