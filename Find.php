<?php
session_start();
if (!isset($_SESSION["name"])) {
    header('Location: MinePage.php');
    exit();
}

require_once 'connect.php'; // Include your database connection file

// Initialize variables
$results = [];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $query = $_GET['query'];

    // Adjust the SQL query to select the necessary columns from your 'estate' table
    $sql = "SELECT ID, IDNumber, UserName, PhoneNumber, AreaNumber, BlockNumber, TotalCatchBlook, CustomerPayment, RemainingAmount, Date, Photo FROM estate WHERE UserName LIKE :query OR AreaNumber LIKE :query OR PhoneNumber LIKE :query";
    $stmt = $database->prepare($sql);
    $stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check if any results found
    if (!$results) {
        $results = []; // Ensure $results is an empty array if no results found
    }
}

if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    $query = $database->prepare("SELECT Photo FROM estate WHERE ID = :id");
    $query->bindParam(':id', $ID);
    $query->execute();
    $fetch = $query->fetch();

    if ($fetch && isset($fetch['Photo'])) {
        $fileNames = explode(',', $fetch['Photo']); // Handle multiple files
        foreach ($fileNames as $fileName) {
            $file = __DIR__ . "/Photo/" . $fileName;
            if (file_exists($file)) {
                $mimeType = mime_content_type($file);
                header("Content-Disposition: attachment; filename=" . basename($file));
                header("Content-Type: " . $mimeType);
                header("Content-Length: " . filesize($file));
                readfile($file);
                exit;
            } else {
                echo "File not found: " . htmlspecialchars($fileName);
                exit;
            }
        }
    } else {
        echo "No file details found.";
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find By Area Number And User Name</title>
    <link rel="stylesheet" href="find.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
</head>
<body>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="GET">
            <h1>Find By Area Number And User Name</h1>
            <div class="input-box">
                <input type="text" name="query" placeholder="Search Area Number Or User Name Or PhoneNumber" required value="<?php echo isset($_GET['query']) ? htmlspecialchars($_GET['query']) : ''; ?>">
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
                        <th>Download</th>
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
                            <a href="download.php?ID=<?php echo $row['ID']; ?>">Download</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button style="display: block; margin-left: auto; margin-right: auto;" type="button" class="bu" onclick="window.location.href = 'Page.php';">Go Back</button>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])): ?>
            <div class="alert alert-danger" role="alert">
                No records found for this query.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
