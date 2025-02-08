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
$row = null; // Initialize $row to null

// Check if ID parameter is set in URL
if (isset($_GET['id'])) {
    $edit_ID = $_GET['id'];

    // Fetch the record to be edited
    $sth = $database->prepare("SELECT * FROM estate WHERE ID = :id");
    $sth->bindParam(':id', $edit_ID);
    $sth->execute();
    $row = $sth->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        // Handle case where no record is found with the given ID
        $error_message = "Record not found.";
    }
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($row)) {
    $ID = $_POST['ID'];
    $IDNumber = $_POST['IDNumber'];
    $UserName = $_POST['UserName'];
    $PhoneNumber = $_POST['PhoneNumber'];
    $BlockNumber = $_POST['BlockNumber'];
    $AreaNumber = $_POST['AreaNumber'];
    $TotalCatchBlook = $_POST['TotalCatchBlook'];
    $CustomerPayment = $_POST['CustomerPayment'];
    $RemainingAmount = $TotalCatchBlook - $CustomerPayment;
    $Date = $_POST['Date'];

    // File upload handling
    $fileNames = $_FILES['Photo']['name'];
    $tmpNames = $_FILES['Photo']['tmp_name'];
    $fileTypes = $_FILES['Photo']['type'];
    $fileSizes = $_FILES['Photo']['size'];
    $location = __DIR__ . "/Photo/";
    $photos = [];

    // Validate IDNumber to ensure it's numeric and exactly 10 digits
    if (!preg_match('/^[0-9]{10}$/', $IDNumber)) {
        $error_message = "ID Number should contain exactly 10 digits.";
    }
    // Validate AreaNumber and BlockNumber to ensure they are numeric
    elseif (!is_numeric($AreaNumber)) {
        $error_message = "Area Number should be numeric.";
    }
    elseif (!is_numeric($BlockNumber)) { 
        $error_message = "Block Number should be numeric.";
    }
    // Validate UserName to ensure it contains only letters (Arabic and English)
    elseif (!preg_match('/^[\p{Arabic}\p{L} ]+$/u', $UserName)) {
        $error_message = "User Name should contain only Arabic and English letters.";
    }
    // Validate PhoneNumber to ensure it's numeric and exactly 10 digits
    elseif (!preg_match('/^[0-9]{9}$/', $PhoneNumber)) {
        $error_message = "Phone Number should be numeric and exactly 10 digits.";
    }
    // Validate file uploads
    elseif (empty($fileNames[0])) {
        $error_message = "Please select at least one photo.";
    }
    // Proceed if all validations pass
    else {
        // Upload each file and store their names in $photos array
        foreach ($fileNames as $key => $fileName) {
            $targetPath = $location . $fileName;
            if (move_uploaded_file($tmpNames[$key], $targetPath)) {
                $photos[] = $fileName;
            } else {
                $error_message = "Error uploading file: " . $fileName;
                break;
            }
        }
        

        if (empty($error_message)) {
            $photoNames = implode(",", $photos); // Implode photos array into a comma-separated string

            // Update the record
            $update = $database->prepare("UPDATE estate SET 
                IDNumber=:IDNumber, 
                UserName=:UserName, 
                PhoneNumber=:PhoneNumber, 
                BlockNumber=:BlockNumber, 
                AreaNumber=:AreaNumber, 
                TotalCatchBlook=:TotalCatchBlook, 
                CustomerPayment=:CustomerPayment, 
                RemainingAmount=:RemainingAmount, 
                Date=:Date, 
                Photo=:Photo 
                WHERE ID=:ID");

            $update->bindParam(':IDNumber', $IDNumber);
            $update->bindParam(':UserName', $UserName);
            $update->bindParam(':PhoneNumber', $PhoneNumber);
            $update->bindParam(':BlockNumber', $BlockNumber);
            $update->bindParam(':AreaNumber', $AreaNumber);
            $update->bindParam(':TotalCatchBlook', $TotalCatchBlook);
            $update->bindParam(':CustomerPayment', $CustomerPayment);
            $update->bindParam(':RemainingAmount', $RemainingAmount);
            $update->bindParam(':Date', $Date);
            $update->bindParam(':Photo', $photoNames); // Store the comma-separated list of uploaded file names
            $update->bindParam(':ID', $ID);

            if ($update->execute()) {
                $success_message = "Data Updated Successfully";
                // Redirect after 2 seconds
                echo '<meta http-equiv="refresh" content="2;url=Page.php">';
            } else {
                $error_message = "Failed to update data!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
    <link rel="stylesheet" href="UP.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
</head>
<body>
<div class="wrapper">
    <form method="post" action="Up.php?id=<?php echo isset($row['ID']) ? $row['ID'] : ''; ?>" enctype="multipart/form-data">
        <h1>Update</h1>
        <?php if ($success_message) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
            <!-- JavaScript redirection -->
            <script>
                setTimeout(function() {
                    window.location.href = "Page.php";
                }, 1000); // 1 second delay
            </script>
        <?php endif; ?>
        <?php if ($error_message) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <input type="hidden" name="ID" value="<?php echo isset($row['ID']) ? $row['ID'] : ''; ?>">
        <div class="input-box">
            <input type="text" name="IDNumber" maxlength="10" pattern="[0-9]{10}" placeholder="Enter ID Number" value="<?php echo isset($row['IDNumber']) ? $row['IDNumber'] : ''; ?>" required>
        </div>
        <div class="input-box">
            <input type="text" name="UserName" placeholder="Enter User Name" value="<?php echo isset($row['UserName']) ? $row['UserName'] : ''; ?>" required>
        </div>
        <div class="input-box">
            <input type="text" name="PhoneNumber" maxlength="9" pattern="[0-9]{9}" placeholder="Enter Phone Number" value="<?php echo isset($row['PhoneNumber']) ? $row['PhoneNumber'] : ''; ?>" required>
        </div>
        <div class="input-box">
            <input type="text" name="AreaNumber" placeholder="Enter Area Number" value="<?php echo isset($row['AreaNumber']) ? $row['AreaNumber'] : ''; ?>" required>
        </div>
        <div class="input-box">
            <input type="text" name="BlockNumber" placeholder="Enter Block Number" value="<?php echo isset($row['BlockNumber']) ? $row['BlockNumber'] : ''; ?>" required>
        </div>
        </div>

        <div class="w">
        <div class="bo">
            <span id="file-name"><?php echo (isset($row['Photo']) ? 'File(s) uploaded' : 'Choose Your File/s'); ?></span>
            <input type="file" name="Photo[]" id="file-upload" onchange="updateFileName()" multiple="multiple">
        </div>
            <div class="input-bo">
                <input type="date" name="Date" placeholder="Enter Date" value="<?php echo isset($row['Date']) ? $row['Date'] : ''; ?>" required>
            </div>
            <div class="input-bo">
                <input type="text" name="TotalCatchBlook" placeholder="Enter Total Catch Blook" value="<?php echo isset($row['TotalCatchBlook']) ? $row['TotalCatchBlook'] : ''; ?>" required oninput="calculateRemainingAmount()" />
            </div>
            <div class="input-bo">
                <input type="text" name="CustomerPayment" placeholder="Enter Customer Payment" value="<?php echo isset($row['CustomerPayment']) ? $row['CustomerPayment'] : ''; ?>" required oninput="calculateRemainingAmount()" />
            </div>
            <div class="input-bo">
                <input type="text" name="RemainingAmount" placeholder="Remaining Amount" value="<?php echo isset($row['RemainingAmount']) ? $row['RemainingAmount'] : ''; ?>" readonly='true' />
            </div>
            <button type="submit" class="bt">Update</button>
        </div>
    </form>
</div>

<script>
    function calculateRemainingAmount() {
        var totalCatchBlook = parseFloat(document.getElementsByName("TotalCatchBlook")[0].value) || 0;
        var customerPayment = parseFloat(document.getElementsByName("CustomerPayment")[0].value) || 0;
        var remainingAmount = totalCatchBlook - customerPayment;
        document.getElementsByName("RemainingAmount")[0].value = remainingAmount;
    }
    
    function updateFileName() {
        var input = document.getElementById('file-upload');
        var output = document.getElementById('file-name');
        var fileNames = Array.from(input.files).map(file => file.name).join(', ');
        output.textContent = fileNames;
    }
</script>
</body>
</html>
