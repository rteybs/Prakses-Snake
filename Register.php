<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <meta http-equiv="X-UA-Compatible" content="ie=edge"> 
    <title>Registration Form</title> 
    <link rel="stylesheet" href="css/auth_style.css">
</head> 
<body> 
    <div class="auth-container">   
        <div class="card-title"> 
            <h3> Reģistrēšanās </h3> 
        </div>  

        <form action="auth/insert.php" method="post" enctype="multipart/form-data"> 
            <input type="text" placeholder="username" name="vusername">
            <input type="email" placeholder="email" name="email"> 
            <input type="password" placeholder="password" name="password">
                     
            <button class="btn btn-primary" name="submit">Submit</button> 
        </form>  
    </div> 
        
</body> 
</html> 