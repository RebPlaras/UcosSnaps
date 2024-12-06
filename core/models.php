<?php  

require_once 'dbConfig.php';

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"result"=> false,
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}

function getAllUsers($pdo) {
	$sql = "SELECT * FROM user_accounts";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getUserByID($pdo, $username) {
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$username]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function insertPhoto($pdo, $photo_name, $username, $description, $photo_id = null, $album_id = null) {
    if (empty($photo_id)) {
        $sql = "INSERT INTO photos (photo_name, username, description, album_id) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$photo_name, $username, $description, $album_id]);
    } else {
        $sql = "UPDATE photos SET photo_name = ?, description = ? WHERE photo_id = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$photo_name, $description, $photo_id]);
    }
}


function getAllPhotos($pdo, $username=null) {
	if (empty($username)) {
		$sql = "SELECT * FROM photos ORDER BY date_added DESC";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
	else {
		$sql = "SELECT * FROM photos WHERE username = ? ORDER BY date_added DESC";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$username]);

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
}


function getPhotoByID($pdo, $photo_id) {
	$sql = "SELECT * FROM photos WHERE photo_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$photo_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


function deletePhoto($pdo, $photo_id) {
	$sql = "DELETE FROM photos WHERE photo_id  = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$photo_id]);

	if ($executeQuery) {
		return true;
	}
	
}

function insertComment($pdo, $photo_id, $username, $description) {
	$sql = "INSERT INTO photos (photo_id, username, description) VALUES(?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$photo_id, $username, $description]);

	if ($executeQuery) {
		return true;
	}
}

function getCommentByID($pdo, $comment_id) {
	$sql = "SELECT * FROM comments WHERE comment_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$comment_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


function updateComment($pdo, $description, $comment_id) {
	$sql = "UPDATE comments SET description = ?, WHERE comment_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$description, $comment_id,]);

	if ($executeQuery) {
		return true;
	}
}

function deleteComment($pdo, $comment_id) {
	$sql = "DELETE FROM comments WHERE comment_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$comment_id]);

	if ($executeQuery) {
		return true;
	}
}

function getAllPhotosJson($pdo) {
	if (empty($username)) {
		$sql = "SELECT * FROM photos";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute();

		if ($executeQuery) {
			return $stmt->fetchAll();
		}
	}
}

function getAllAlbums($pdo, $username = null) {
    $sql = $username
        ? "SELECT * FROM albums WHERE username = ?"
        : "SELECT * FROM albums";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($username ? [$username] : []);
    $albums = $stmt->fetchAll();

    foreach ($albums as &$album) {
        $sql = "SELECT * FROM photos WHERE album_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$album['album_id']]);
        $album['photos'] = $stmt->fetchAll();
    }
    return $albums;
}


function getPhotosByAlbumID($pdo, $album_id) {
    $sql = "SELECT * FROM photos WHERE album_id = ? ORDER BY date_added DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$album_id]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getAlbumByID($pdo, $album_id) {
    $sql = "SELECT * FROM albums WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$album_id]);
    $album = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($album) {
        // Fetch photos associated with the album
        $album['photos'] = getPhotosByAlbumID($pdo, $album_id);

        // If no photos found, make sure the 'photos' key exists
        if (empty($album['photos'])) {
            $album['photos'] = []; // Set an empty array if no photos
        }
    }

    return $album;
}


function updateAlbum($pdo, $album_id, $album_name) {
    $sql = "UPDATE albums SET album_name = ? WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$album_name, $album_id]);
}

function deleteAlbum($pdo, $album_id) {
    $sql = "DELETE FROM albums WHERE album_id = ?";
    $stmt = $pdo->prepare($sql);

    // Optionally, delete associated photos as well
    $deletePhotos = "DELETE FROM photos WHERE album_id = ?";
    $stmtPhotos = $pdo->prepare($deletePhotos);
    $stmtPhotos->execute([$album_id]);

    return $stmt->execute([$album_id]);
}


function insertAlbum($pdo, $albumName, $username) {
    try {
        $sql = "INSERT INTO albums (album_name, username, date_created) VALUES (?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$albumName, $username])) {
            return $pdo->lastInsertId();
        } else {
            throw new Exception('Failed to insert album.');
        }
    } catch (PDOException $e) {
        echo "PDO Error: " . $e->getMessage();
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
}

function insertPhotoToAlbum($pdo, $photoName, $description, $albumID) {
    $stmt = $pdo->prepare("INSERT INTO photos (photo_name, description, album_id) VALUES (:photo_name, :description, :album_id)");
    return $stmt->execute([
        ':photo_name' => $photoName,
        ':description' => $description,
        ':album_id' => $albumID
    ]);
}
?>