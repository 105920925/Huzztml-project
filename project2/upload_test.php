<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['resume'])) {
    $uploadDir = 'db/uploads/';
    $uploadFile = $uploadDir . basename($_FILES['resume']['name']);
    
    if (move_uploaded_file($_FILES['resume']['tmp_name'], $uploadFile)) {
        echo "File is valid, and was successfully uploaded.";
    } else {
        echo "Possible file upload attack!";
    }
}
?>

<form enctype="multipart/form-data" action="upload_test.php" method="POST">
    <input type="file" name="resume">
    <input type="submit" value="Upload">
</form>
