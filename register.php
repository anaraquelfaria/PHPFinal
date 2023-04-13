<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
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
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<br><br><br><br><br><br><br><br>

<div class="row">
 <div class="col-sm-1">
  </div>
  <div class="col-sm-5">
  <br><br><br><br><br>
  <img src="data:image/png;base64,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" alt="Lights" style="width:100%">
  </div>
  <div class="col-sm-1">
  </div>
  <div class="col-sm-5">
  <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
    </div>
  
</body>
</html>