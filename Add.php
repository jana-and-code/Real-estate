<?php
session_start();
if (!isset($_SESSION["name"])) {
    header('Location: MinePage.php');
    exit();
}

include 'connect.php'; // Include your database connection file

// Initialize variables
$success_message = "";
$error_message = "";

if (isset($_POST['Send'])) {
    // Retrieve form data
    $IDNumber = $_POST['IDNumber'];
    $UserName = $_POST['UserName'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $AreaNumber = $_POST['AreaNumber'];
    $BlockNumber = $_POST['BlockNumber'];
    $Date = $_POST['Date'];
    $TotalCatchBlook = $_POST['TotalCatchBlook'];
    $CustomerPayment = $_POST['CustomerPayment'];
    $RemainingAmount = $TotalCatchBlook - $CustomerPayment;

    // File upload handling
// File upload handling
$fileNames = $_FILES['Photo']['name'];
$tmpNames = $_FILES['Photo']['tmp_name'];
$fileTypes = $_FILES['Photo']['type'];
$fileSizes = $_FILES['Photo']['size'];
$location = "photo/"; // Path to the photo folder
$photos = [];

// Validate file uploads
if (empty($fileNames[0])) {
    $error_message = "Please select at least one photo.";
} else {
    // Upload each file and store their names in $photos array
    foreach ($fileNames as $key => $fileName) {
        $targetPath = $location . basename($fileName);
        $fileType = $fileTypes[$key];
        $fileSize = $fileSizes[$key];

        // Check for upload errors
        if ($_FILES['Photo']['error'][$key] != UPLOAD_ERR_OK) {
            $error_message = "Error uploading file: " . $fileName;
            break;
        }

        // Move the file to the target directory
        if (!move_uploaded_file($tmpNames[$key], $targetPath)) {
            $error_message = "Error uploading file(s).";
            break;
        }

        $photos[] = $fileName; // Store file names for database insertion
    }
}

if (empty($error_message)) {
    $photoNames = implode(",", $photos); // Implode photos array into a comma-separated string

    // Prepare and execute the INSERT statement
    $AddData = $database->prepare("INSERT INTO estate (IDNumber, UserName, PhoneNumber, AreaNumber, BlockNumber, Date, TotalCatchBlook, CustomerPayment, RemainingAmount, Photo)
        VALUES (:IDNumber, :UserName, :PhoneNumber, :AreaNumber, :BlockNumber, :Date, :TotalCatchBlook, :CustomerPayment, :RemainingAmount, :Photo)");
    
    $AddData->bindParam(':IDNumber', $IDNumber);
    $AddData->bindParam(':UserName', $UserName);
    $AddData->bindParam(':PhoneNumber', $PhoneNumber);
    $AddData->bindParam(':AreaNumber', $AreaNumber);
    $AddData->bindParam(':BlockNumber', $BlockNumber);
    $AddData->bindParam(':Date', $Date);
    $AddData->bindParam(':TotalCatchBlook', $TotalCatchBlook);
    $AddData->bindParam(':CustomerPayment', $CustomerPayment);
    $AddData->bindParam(':RemainingAmount', $RemainingAmount);
    $AddData->bindParam(':Photo', $photoNames);

    if ($AddData->execute()) {
        $success_message = "Data Added Successfully";
        // Redirect after 2 seconds
        echo '<meta http-equiv="refresh" content="2;url=Page.php">';
    } else {
        $error_message = "Failed To Add Data!";
    }
}

    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Adding.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
    <title>Adding</title>
</head>
<body>
<main>
    <div class="wrapper">
        <h1>Adding New Block</h1>
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <div class="input-box">
                <input type="text" name="IDNumber" maxlength="10" pattern="[0-9]{10}" placeholder="Enter ID Number" required>
            </div>
            <div class="input-box">
                <input type="text" name="UserName" placeholder="Enter User Name" pattern="^[\p{Arabic}\p{L} ]+$" title="Only Arabic and English letters are allowed" required>
            </div>
            <div class="input-box">
                 <input type="text" name="PhoneNumber" maxlength="10" pattern="[0-9]{10}" placeholder="Enter Phone Number" required>
            </div>
            <div class="input-box">
                <input type="text" name="AreaNumber" placeholder="Enter Area Number" required>
            </div>
            <div class="input-box">
                <input type="text" name="BlockNumber" placeholder="Enter Block Number" required>
            </div>
            </div>
            
            <div class="w">
            <div class="bo">
                <span id="file-name">Choose Your File/s</span>
                <input type="file" name="Photo[]" id="file-upload" required onchange="updateFileName()" multiple="multiple">
            </div>

            <div class="input-bo">
                <input type="date" name="Date" placeholder="Enter Date" required>
            </div>
            <div class="input-bo">
                <input type="number" name="TotalCatchBlook" placeholder="Enter Total Catch Blook" required  oninput="calculateRemainingAmount()" />
            </div>
            <div class="input-bo">
                <input type="number" name="CustomerPayment" placeholder="Enter Customer Payment" required  oninput="calculateRemainingAmount()" />
            </div>
            <div class="input-bo">
                <input type="text" name="RemainingAmount" placeholder="Remaining Amount" readonly="true">
            </div>
            <button type="submit" name="Send" class="bt">Adding</button>
        </form>
    </div>
</main>
<script>
    function updateFileName() {
        var input = document.getElementById('file-upload');
        var output = document.getElementById('file-name');
        var fileNames = [];
        for (var i = 0; i < input.files.length; i++) {
            fileNames.push(input.files[i].name);
        }
        output.textContent = fileNames.join(', ');
    }

    function calculateRemainingAmount() {
        var totalCatchBlook = parseFloat(document.getElementsByName("TotalCatchBlook")[0].value) || 0;
        var customerPayment = parseFloat(document.getElementsByName("CustomerPayment")[0].value) || 0;
        var remainingAmount = totalCatchBlook - customerPayment;
        document.getElementsByName("RemainingAmount")[0].value = remainingAmount;
    }

    function validateForm() {
        var files = document.querySelector('[name="Photo[]"]').files;
        var totalCatchBlook = parseFloat(document.getElementsByName("TotalCatchBlook")[0].value) || 0;
        var customerPayment = parseFloat(document.getElementsByName("CustomerPayment")[0].value) || 0;
        if (totalCatchBlook < 0) {
            alert("Total Catch Blook cannot be negative.");
            return false;
        }
        if (customerPayment < 0) {
            alert("Customer Payment cannot be negative.");
            return false;
        }
        if (customerPayment > totalCatchBlook) {
            alert("Customer Payment cannot be greater than Total Catch Blook.");
            return false;
        }
        if (files.length === 0) {
            alert("Please upload at least one photo.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>
