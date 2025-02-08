<?php
require_once 'connect.php'; // Include your database connection file

if (isset($_REQUEST['ID'])) {
    $ID = $_REQUEST['ID'];
    
    // Prepare and execute SQL query to fetch file details based on ID
    $query = $database->prepare("SELECT * FROM `estate` WHERE `ID` = :id");
    $query->bindParam(':id', $ID);
    $query->execute();
    $fetch = $query->fetch();

    if ($fetch && isset($fetch['Photo'])) {
        $photos = explode(',', $fetch['Photo']); // Assuming filenames are comma-separated
        
        // Create a temporary zip file
        $zip = new ZipArchive();
        $zipFilename = tempnam(sys_get_temp_dir(), 'zip');
        if ($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE) {
            exit("Cannot open <$zipFilename>\n");
        }

        foreach ($photos as $photo) {
            $file = __DIR__ . "/photo/" . trim($photo);
            if (file_exists($file)) {
                $zip->addFile($file, basename($file));
            }
        }
        $zip->close();

        // Send the zip file to the user
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="images.zip"');
        header('Content-Length: ' . filesize($zipFilename));
        readfile($zipFilename);

        // Delete the temporary zip file
        unlink($zipFilename);

        exit();
    } else {
        echo "File not found.";
    }
} else {
    echo "Invalid request.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Uploaded Images</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <a class="navbar-brand" href="https://sourcecodester.com">Sourcecodester</a>
        </div>
    </nav>
    <div class="col-md-3"></div>    
    <div class="col-md-6 well">
        <h3 class="text-primary">Uploaded Images</h3>
        <hr style="border-top:1px dotted #ccc;"/>
        <br />
        <table class="table table-bordered">
            <thead class="alert-info">
                <tr>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                require_once 'connect.php'; // Include your database connection file
                $query = $database->prepare("SELECT * FROM `estate`");
                $query->execute();
                while ($fetch = $query->fetch()) {
                    ?>
                    <tr>
                        <td>
                            <a href="download.php?ID=<?php echo $fetch['ID']; ?>" class="btn btn-primary">Download All Images</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body> 
</html>
