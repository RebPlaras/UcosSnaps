<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Check if `album_id` is provided
if (!isset($_GET['album_id'])) {
    die("Album ID is required!");
}

$album_id = $_GET['album_id'];

// Fetch album details
$album = getAlbumByID($pdo, $album_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $album_name = $_POST['album_name'];

    // Update the album name
    $updateStatus = updateAlbum($pdo, $album_id, $album_name);

    if ($updateStatus) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error updating album!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Album</title>
</head>
<body>
    <h1>Edit Album</h1>
    <form action="" method="POST">
        <label for="album_name">Album Name:</label>
        <input type="text" name="album_name" value="<?php echo $album['album_name']; ?>" required>
        <button type="submit">Update Album</button>
    </form>
</body>
</html>
