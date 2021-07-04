<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $state = "";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        echo '<script>alert("Please check Database Connection!")</script>'; 
        $state = "false";  
    }else{
        echo '<script>alert("Database Successfully connected!")</script>';
        $state = "true";
    }

    echo "<script>location.replace('./index.php/?state=".$state."');</script>";
?>

