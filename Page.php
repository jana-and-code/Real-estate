<?php
session_start();
if (!isset($_SESSION["name"])) {
    header('Location: MinePage.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page</title>
    <link rel="stylesheet" href="page.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
</head>
<body>
<div class="wrapper"> 
    <h1>Main Menu Screen</h1><br>
    <button type="button" class="btn" onclick="window.location.href = 'Add.php';">Add New Block</button> 
    <button type="button" class="btn" onclick="window.location.href = 'Update.php';">Update Block Info</button>
    <button type="button" class="btn" onclick="window.location.href = 'Delet.php';">Delete Blocks</button>
    <button type="button" class="btn" onclick="window.location.href = 'Find.php';">Find Block</button>
    <button type="button" class="btn" onclick="window.location.href = 'Show.php';">Show Block List</button>
    <button type="button" class="btn" onclick="window.location.href = 'Exit.php';">Exit</button>
</div>
</body>
</html>
