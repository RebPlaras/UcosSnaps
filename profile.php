<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>

<?php  
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>User Profile</title>
	<link rel="stylesheet" href="styles/styles.css">
</head>
<body>
	<?php include 'navbar.php'; ?>

	<?php 
	$getUserByID = getUserByID($pdo, $_GET['username']); 
	?>

	<div class="container" style="display: flex; justify-content: center;">
		<div class="userInfo" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; text-align: center;">
			<h3>Username: <span style="color: blue"><?php echo $getUserByID['username']; ?></span></h3>
			<h3>First Name: <span style="color: blue"><?php echo $getUserByID['first_name']; ?></span></h3>
			<h3>Last Name: <span style="color: blue"><?php echo $getUserByID['last_name']; ?></span></h3>
			<h3>Date Joined: <span style="color: blue"><?php echo $getUserByID['date_added']; ?></span></h3>
		</div>
	</div>

	<?php 
	$getAllAlbums = getAllAlbums($pdo, $_GET['username']); 
	if (empty($getAllAlbums)) {
		echo '<p style="text-align: center; margin-top: 20px;">No albums to display.</p>';
	} else {
		foreach ($getAllAlbums as $album) { 
	?>
		<div class="albums" style="display: flex; justify-content: center; margin-top: 25px;">
			<div class="albumContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray;width: 50%; text-align: center; padding: 20px;">
				<h3><?php echo $album['album_name']; ?></h3>
				<p>Created by: <span style="color: blue;"><?php echo $album['username']; ?></span></p>
				<p><i><?php echo $album['date_created']; ?></i></p>
				<a href="viewalbum.php?album_id=<?php echo $album['album_id']; ?>">View Album</a>

				<?php if ($_SESSION['username'] == $album['username']) { ?>
					<br><br>
					<a href="editalbum.php?album_id=<?php echo $album['album_id']; ?>" style="float: right;"> Edit </a>
					<br><br>
					<a href="deletealbum.php?album_id=<?php echo $album['album_id']; ?>" style="float: right;"> Delete</a>
				<?php } ?>
			</div>
		</div>
	<?php 
		}
	} 
	?>

</body>
</html>
