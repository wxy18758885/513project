<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address604 = $salary = $username=$password="";
$name_err = $address604_err = $salary_err = $username_err=$password_err="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }


    // Validate name
    $input_name = trim($_POST["name"]);
    if(empty($input_name)){
        $name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $name_err = "Please enter a valid name.";
    } else{
        $name = $input_name;
    }
    
    // Validate address
    $input_address604 = trim($_POST["address"]);
    if(empty($input_address604)){
        $address604_err = "Please enter an address.";     
    } else{
        $address604 = $input_address604;
    }
    
    // Validate salary
    $input_salary = trim($_POST["salary"]);
    if(empty($input_salary)){
        $salary_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_salary)){
        $salary_err = "Please enter a positive integer value.";
    } else{
        $salary = $input_salary;
    }
    
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address604_err) && empty($salary_err) && empty($username_err) && empty($password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (name, address604, salary,username,password) VALUES (?, ?, ?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssss", $param_name, $param_address604, $param_salary,$param_username,$param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address604 = $address604;
            $param_salary = $salary;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: welcome.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>












<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>bakery</title>
    <link href="./css/b6.css" rel="stylesheet" type="text/css" media="all" />

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body style="background-color:  bisque;">
    <div class="header-wrapper">
        <div class="header" class="container">
            <div class="menu" >
                <ul>
                    <li ><a href="index.html" accesskey="1" title="">Home</a></li>
				<li ><a href="aboutus.html" accesskey="2" title="">About US</a></li>
				<li><a href="upload.html" accesskey="3" title="">careers</a></li>
				<li><a href="orderonline.php" accesskey="4" title="">Order online </a></li>
				<li ><a href="contactus.html" accesskey="5" title="">Contact Us</a></li>
				<li class="active"><a href="register.php" accesskey="6" title="">Register</a></li>
                </ul>
            </div>
            <div class="logo">
                <img src="./img/logo1.jpg" alt="">
                <h1>Welcome to Luca's Loaves</h1>
            </div>
            
        </div>
    </div>
    <div style="background: url(./img/register.jpg);" class="z">
        <div id="welcome" class="container">
            <div class="title">
                <h1>Register!</h1>
                <p>click here <a href="login.php">link</a> to login if you are already a member.</p>
                <h2>Register as a bread lover.</h2>
            </div>
        </div>
        



        <div class="wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="mt-5">Create Account</h2>
                        <p>Please fill in your credentials.</p>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                                <span class="invalid-feedback"><?php echo $name_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="address" class="form-control <?php echo (!empty($address604_err)) ? 'is-invalid' : ''; ?>"><?php echo $address604; ?></textarea>
                                <span class="invalid-feedback"><?php echo $address604_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Salary</label>
                                <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                                <span class="invalid-feedback"><?php echo $salary_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>User Name</label>
                                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                                <span class="invalid-feedback"><?php echo $username_err;?></span>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                                <span class="invalid-feedback"><?php echo $password_err;?></span>
                            </div>
                            <input type="submit" class="btn btn-primary" value="Submit">
                            <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                        </form>
                        <a href="./index.php" style="font-size: 35px;">user manager</a>
                    </div> 
                </div>        
            </div>
        </div>   


    </div>

    <div class="footer">
        <div class="db">
            <div class="wz" >
                <h3>
                    PinYin:Wang Xinyu 
                </h3>
                <h3>
                    class number:20IT1
                </h3>
                <h3>
                    English name:Wendy  
                </h3>
    
            </div>
            <img src="./img/logo1.jpg" alt="">
        </div>
        
        
        <p>Luca's Loaves@example.com</p>


    </div>
