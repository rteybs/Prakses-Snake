<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <title>Registration Form</title> 
    <link rel="stylesheet" href="css/AuthStyle.css">
</head> 
<body> 
    <div class="auth-container">   
        <div class="card-title"> 
            <h3> Sign in </h3> 
        </div>  

        <form action="auth/insert.php" method="post" enctype="multipart/form-data"> 
            <input type="text" placeholder="username" name="Username">
            <input type="email" placeholder="email" name="Email"> 
            <input type="password" placeholder="password" name="Password">
            <input type="file" name="Avatar" accept="image/png, image/jpeg">
                     
            <button class="btn btn-primary" name="submit">Submit</button> 
        </form>  
    </div> 
        
</body> 
</html> 