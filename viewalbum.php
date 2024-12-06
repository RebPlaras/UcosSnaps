<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if album_id is passed in the URL
if (!isset($_GET['album_id'])) {
    echo "Album not specified.";
    exit;
}

// Fetch the album and its photos
$album_id = $_GET['album_id'];
$album = getAlbumByID($pdo, $album_id);

if (!$album) {
    echo "Album not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album['album_name']); ?></title>
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="albumContainer" style="text-align: center; margin-top: 20px;">
        <h2><?php echo htmlspecialchars($album['album_name']); ?></h2>
        <p>Created by: <a href="profile.php?username=<?php echo htmlspecialchars($album['username']); ?>">
            <?php echo htmlspecialchars($album['username']); ?></a></p>
        <p><i><?php echo $album['date_created']; ?></i></p>

        <div class="albumPhotos" style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
            <?php foreach ($album['photos'] as $photo) { ?>
                <div style="width: 200px;">
                    <img src="images/<?php echo htmlspecialchars($photo['photo_name']); ?>" alt="" style="width: 100%;">
                    <p><?php echo htmlspecialchars($photo['description']); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

    <?php if ($_SESSION['username'] == $album['username']) { ?>
        <div style="margin-top: 20px;">
            <a href="editalbum.php?album_id=<?php echo $album['album_id']; ?>">Edit Album</a> |
            <a href="deletealbum.php?album_id=<?php echo $album['album_id']; ?>">Delete Album</a>
        </div>
    <?php } ?>
</body>
</html>
