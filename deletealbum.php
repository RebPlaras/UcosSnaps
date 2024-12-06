<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

// Check if `album_id` is provided
if (!isset($_GET['album_id'])) {
    die("Album ID is required!");
}

$album_id = $_GET['album_id'];

// Only process deletion if `confirmDelete` is set
if (isset($_POST['confirmDelete'])) {
    $deleteStatus = deleteAlbum($pdo, $album_id);

    if ($deleteStatus) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting album!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Album</title>
</head>
<body>
    <h1>Are you sure you want to delete this album?</h1>
    <p>This action cannot be undone.</p>
    <form method="POST">
        <button type="submit" name="confirmDelete">Yes, Delete</button>
        <button type="button" onclick="window.location.href='index.php';">Cancel</button>
    </form>
</body>
</html>
