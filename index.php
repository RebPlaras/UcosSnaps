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
	<title>Photo Albums</title>
	<link rel="stylesheet" href="styles/styles.css">
	<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</head>
<body>
	<?php include 'navbar.php'; ?>

	<!-- Create Album Form -->
	<div class="createAlbumForm" style="display: flex; justify-content: center; margin-bottom: 20px;">
		<form action="core/handleForms.php" method="POST">
			<p>
				<label for="albumName">Album Name</label>
				<input type="text" name="albumName" required>
			</p>
			<input type="submit" name="createAlbumBtn" value="Create Album" style="margin-top: 10px;">
		</form>
	</div>

	<!-- Add Images to an Album Form -->
	<div class="addPhotosForm" style="display: flex; justify-content: center;">
		<form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
			<p>
				<label for="albumID">Select Album</label>
				<select name="albumID" required>
					<?php $albums = getAllAlbums($pdo); ?>
					<?php foreach ($albums as $album) { ?>
						<option value="<?php echo $album['album_id']; ?>"><?php echo $album['album_name']; ?></option>
					<?php } ?>
				</select>
			</p>
			<p>
				<label for="photoDescriptions[]">Descriptions</label><br>
				<textarea name="photoDescriptions[]" placeholder="Description for Image"></textarea>
			</p>
			<p>
				<label for="images">Upload Images (Max 4)</label>
				<input type="file" name="images[]" multiple accept="image/*" required>
			</p>
			<input type="submit" name="addImagesBtn" value="Add Images to Album" style="margin-top: 10px;">
		</form>
	</div>

	<!-- Display Albums -->
	<?php foreach ($albums as $album) { ?>
		<div class="album" style="display: flex; flex-direction: column; align-items: center; margin-top: 25px;">
			<div class="albumContainer" style="background-color: ghostwhite; border-style: solid; border-color: gray; width: 60%;">
				<h2><?php echo $album['album_name']; ?></h2>
				<p>Created by: <a href="profile.php?username=<?php echo $album['username']; ?>"><?php echo $album['username']; ?></a></p>
				<p><i><?php echo $album['date_created']; ?></i></p>
				<div class="albumImages" style="display: flex; gap: 10px; flex-wrap: wrap;">
					<?php foreach ($album['photos'] as $image) { ?>
						<div style="width: 48%;">
							<img src="images/<?php echo $image['photo_name']; ?>" alt="" style="width: 100%;">
							<p><?php echo $image['description']; ?></p>
						</div>
					<?php } ?>
				</div>
				<?php if ($_SESSION['username'] == $album['username']) { ?>
					<a href="editalbum.php?album_id=<?php echo $album['album_id']; ?>" style="float: right;"> Edit </a>
					<br>
					<br>
					<a href="deletealbum.php?album_id=<?php echo $album['album_id']; ?>" style="float: right;"> Delete</a>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</body>
</html>
