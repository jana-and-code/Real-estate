<?php
session_start();
if (!isset($_SESSION["name"])) {
    header('Location: MinePage.php');
    exit();
}
?>
<?php
include 'connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="icon" type="image/jpg"  href="GettyImages.jpg" />
    <title>Show Block list</title>
</head>
<body>
   
<style>
        *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;

}
body{
    display: flex;
    justify-content: center ;
    align-items: center;
    height: auto;
    width: auto;
    min-height: 100vh;
    background: url(GettyImages.jpg)no-repeat;
    background-size: cover;
    background-position:center ;
}
.wrapper{
    
    height: auto;
    width: auto;
    background-color: rgba(0, 0, 0, 0.566);
    color: white;
    border-radius: 12px;
    padding: 20px 40px ;
}
       
.wrapper .btn{
    width: 20%;
    height: 45px;
    background: #fff;
    border: none;
    outline: none;
    border-radius: 40px;
    cursor: pointer;
    font-size: #333;
    font-weight: 600;
      
}    

        .table_component table {
            border-radius: 8px 8px 6px 6px;
            overflow: hidden;
            border-collapse: collapse;
            text-align: center;
            height: auto;
            width: auto; 
        }
        
        .table_component th {
            border: 0px outset ;
            background-color: #09287c;
            color: #ffffff;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.822);
           
        }
        
        .table_component td {
            border: 0px outset #e2e6e9;
            color:black;
            

        }
        
        .table_component tr:nth-child(even) td {
            background-color: #D2D4DE;
            
        }
        
        .table_component tr:nth-child(odd) td {
            background-color: #ffffff;
          
        }

        .table_component tbody tr.active-row {
               font-weight: bold;
        }    
    </style>


<?php
$query = "SELECT * FROM estate ";
$d = $database->query($query);
?>
<div class="wrapper"> 
<div class="table_component" role="region" tabindex="6">  
<table >
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
                </tr>
            </thead>
            <?php
            foreach($d as $result){
            ?>
 <tbody>
   <tr class="active-row">
     <td><?php echo $result['IDNumber'];?></td>
     <td ><?php echo $result['UserName'];?> </td>
     <td ><?php echo $result['PhoneNumber'];?> </td>
     <td ><?php echo $result['AreaNumber'];?></td>
     <td><?php echo $result['BlockNumber'];?></td>
     <td><?php echo$result['TotalCatchBlook'];?></td>
     <td><?php echo$result['CustomerPayment'];?></td>
     <td><?php echo$result['RemainingAmount'];?></td>
     <td><?php echo$result['Date'];?></td>
  </tr>
  
<?php 
}
?>
                 </tbody>
                </table>
    </div><br><br>
    <button style="display: block; margin-left: auto; margin-right: auto;" type="submit" class="btn" onclick="window.location.href = 'Page.php';">Go Back</button>
 </div>
</body>
</html>
          
 
        
   