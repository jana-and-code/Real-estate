<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="MinePage.css">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
    <title>Login</title>
</head>
<body>
<main>
<?php
session_start();
include 'connect.php';

if (isset($_POST["login"])) {
    $name = strip_tags(trim($_POST["name"]));
    $query = $database->prepare("SELECT * FROM login WHERE Name = ?");
    $query->execute(array($name));
    $control = $query->fetch(PDO::FETCH_OBJ);
    
    if ($control) {
        $_SESSION["name"] = $name;
        header('Location: Page.php');
        exit(); // Ensure that script stops executing after redirection
    } else {
      
    }
}
?>
<div class="wrapper">
    <form action="" method="post">
    <?php if (isset($_POST["login"])){ ?>
            <div class="alert alert-danger" srole="alert"  >
                This name does not exist !
            </div>
        <?php } ?>
        <h1>Login</h1>
        <div class="input-box">
            <input type="text" placeholder="Name" name="name" required />
            <i class='bx bxs-user bx-tada'></i>
        </div>
        <button type="submit" class="btn" name="login">Login</button>
    </form>
</div>
</main>
</body>
</html>
