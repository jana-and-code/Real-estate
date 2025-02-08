<?php
$username = "root";
$password = "";
$db = "realestate";
try{

$database = new PDO("mysql:host=localhost; dbname=$db;", $username, $password);
//echo"Successfully connected to the database".$db;

} catch (PDOException $error) {

    echo" Connecton Failed: " , $error->getMessage();


}

?>