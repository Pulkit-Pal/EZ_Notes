<?php
include('db.php');

$targetDirectory = "uploads/";
$targetFile = $targetDirectory . basename($_FILES["file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }
}

if ($_FILES["file"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($imageFileType != "pdf") {
    echo "Sorry, only PDF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        $filename = basename( $_FILES["file"]["name"]);
        $filePath = $targetFile;

        // Insert file details into the database
        $stmt = $conn->prepare("INSERT INTO files (filename, file_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $filename, $filePath);
        $stmt->execute();

        echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>


<?php
include('db.php');

$result = $conn->query("SELECT * FROM files");

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<a href='".$row['file_path']."' target='_blank'>".$row['filename']."</a><br>";
    }
} else {
    echo "0 results";
}
?>
