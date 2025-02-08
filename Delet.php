<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["name"])) {
    header('Location: MinePage.php');
    exit();
}

// Include database connection
include 'connect.php';

// Process deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ID'])) {
    $ID = $_POST['ID'];

    // Prepare and execute deletion query
    $deleteStmt = $database->prepare("DELETE FROM estate WHERE ID = :id");
    $deleteStmt->bindParam(':id', $ID);

    if ($deleteStmt->execute()) {
        $_SESSION['message'] = 'Record deleted successfully!';
        echo '<meta http-equiv="refresh" content="1;url=Delet.php">';
    } else {
        $_SESSION['message'] = 'Failed to delete record!';
    }

}

// Initialize variables
$results = [];

// Process form submission to fetch records
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $query = $_GET['query'];

    // Prepare SQL query to fetch records based on search criteria
    $sql = "SELECT ID, IDNumber, UserName, PhoneNumber, AreaNumber, BlockNumber, TotalCatchBlook, CustomerPayment, RemainingAmount, Date FROM estate WHERE UserName LIKE :query OR AreaNumber LIKE :query";
    $stmt = $database->prepare($sql);
    $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
    $stmt->execute();

    // Fetch results into associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete</title>
    <link rel="stylesheet" href="D_P.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
</head>
<body>

<div class="wrapper">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
        <h1>Delete</h1>
        <div class="input-box">
            <input type="text" name="query" placeholder="Search Area Number Or User Name . . ." required value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
        </div>
        <button type="submit" class="btn"><i class='bx bx-search bx-tada'></i></button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($results)): ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>IDNumber</th>
                    <th>UserName</th>
                    <th>PhoneNumber</th>
                    <th>AreaNumber</th>
                    <th>BlockNumber</th>
                    <th>TotalCatchBlook</th>
                    <th>CustomerPayment</th>
                    <th>RemainingAmount</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr class="active-row">
                        <td><?php echo htmlspecialchars($row['IDNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['UserName']); ?></td>
                        <td><?php echo htmlspecialchars($row['PhoneNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['AreaNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['BlockNumber']); ?></td>
                        <td><?php echo htmlspecialchars($row['TotalCatchBlook']); ?></td>
                        <td><?php echo htmlspecialchars($row['CustomerPayment']); ?></td>
                        <td><?php echo htmlspecialchars($row['RemainingAmount']); ?></td>
                        <td><?php echo htmlspecialchars($row['Date']); ?></td>
                        <td>
                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="ID" value="<?php echo $row['ID']; ?>">
                                <button type="submit" class="b">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button style="display: block; margin: 20px auto;" class="bu" onclick="window.location.href = 'Page.php';">Go Back</button>

    <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])): ?>
        <div class="alert alert-danger" role="alert">
            No records found based on the search criteria.
        </div>
    <?php endif; ?>

    <?php
    // Display session message if set
    if (isset($_SESSION['message'])) {
        echo '<div class="alert alert-success" role="alert">'.htmlspecialchars($_SESSION['message']).'</div>';
        unset($_SESSION['message']); // Clear session message after displaying
    }
    ?>
</div>

</body>
</html>

<?php
// Close database connection if needed
$database = null;
?>
