<?php 
require_once __DIR__ . '/../includes/connection.php';

if(isset($_POST['submit'])) 
{ 
    if(empty($_POST['Username'])    || 
       empty($_POST['Email'])  || 
       empty($_POST['Password'])) 
    { 
        echo 'Lūdzu aizpildiet visus laukus!'; 
    } 
    else 
    { 
        $Username = $_POST['Username']; 
        $Email = $_POST['Email']; 
        $Password = $_POST['Password'];
    
        
        
            $hashed_password = password_hash($Password, PASSWORD_DEFAULT);

            $query = "INSERT INTO user (Username, Email, Password) 
                      VALUES ('$Username', '$Email', '$hashed_password')";
            $result = mysqli_query($con, $query); 

            if($result) 
            { 
                header("Location: ../index.php");
                exit();
            } 
            else 
            { 
                echo "Kļūda: " . mysqli_error($con); 
            } 
        }
    } 
else 
{ 
    header("Location: ../Register.php"); 
    exit();
} 
?>