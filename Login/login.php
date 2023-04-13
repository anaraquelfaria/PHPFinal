<?php
// Initialize the session

 
// Check if the user is already logged in, if yes then redirect him to welcome page
/*if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}*/
 
// Include config file
//require_once "config.php";


// Define variables and initialize with empty values

session_start();
$username = $password = $pass = "";
$username_err = $password_err = $login_err  = "";


 
// Processing form data when form is submitted
if(isset($_POST['login_botao'])){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', $username);
    define('DB_PASSWORD', $password);
    define('DB_NAME', 'g10');

     
/* Attempt to connect to MySQL database */
if(!empty($password) && !empty($username)) {
    $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $_SESSION['link'] = $link; 
    

    if($link){
        $querytipo = mysqli_query ($link, "SELECT `Tipo` FROM `utilizador` WHERE `Nome` = '$username'");    
        $tipoarray = mysqli_fetch_array($querytipo); 
         $tipo=$tipoarray['Tipo'];
         echo $tipo;
		
        if($tipo==="investigador"){
			header("Location: ../Investigador/Culturas.php");
			}
		if($tipo==="tecnico"){
			header("Location: ../Tecnico/perfil.php");
		}
		if($tipo==="administrador"){
			header("Location: ../Admin/Utilizadores.php");
		}
	   //  A mudar para a dashInvestigadores
    } else {
        $password_err = "Password ou Username incorreto.";
		 //die('Connect Error: ' . mysqli_connect_error());
    }
}
}
    // Validate credentials
  
        
    
	
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body

 <br><br><br><br><br><br><br><br><br>

<div class="row">
 <div class="col-sm-2">
  </div>
  <div class="col-sm-4">
  <br><br><br><br><br>
  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Logo_ISCTE_Instituto_Universit%C3%A1rio_de_Lisboa.svg/1200px-Logo_ISCTE_Instituto_Universit%C3%A1rio_de_Lisboa.svg.png" width="500";>
  </div>
  <div class="col-sm-1">
  </div>
  <div class="col-sm-5">
 <div class="wrapper">
        <h4>Monitorização de Culturas</h4>
        <p>Insira as suas credenciais.</p>

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" name="login_botao" class="btn btn-primary" value="Login">
            </div>
        </form>
    </div>
  
  
  
  </div>
</div>
</body>
</html>