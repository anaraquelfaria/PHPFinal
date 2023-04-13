<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
 
// Define variables and initialize with empty values
$uname = $password = $confirm_password = $email = $foto = "";
$uname_err = $password_err = $confirm_password_err = $email_err = "";
$zona = "SELECT * FROM `zona`";

 
// CREATE USER: Processing form data when form is submitted
if(isset($_POST['criar_utilizador_botao'])){
	
    // Validate username    										1. USER
	if(empty($_POST["username_label"])){
        $uname_err = "Please enter a username.";
    } else{
		$uname = $_POST['username_label'];
		$val = mysqli_query ($link, "SELECT `IDUtilizador` FROM `utilizador` WHERE `Nome` = '$uname'");
		
		if (mysqli_num_rows($val) > 0){
			$uname_err = "This username is already taken.";
		} 
	}
	// Validate password															2.PASS
    if(empty($_POST["password_label"])){
        $pword_err = "Please enter a password.";     
    } elseif(strlen($_POST["password_label"]) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } elseif($_POST["password_label"] !== $_POST["confirm_password_label"]){
		$password_err = "Password not equal.";
	}else{
        $password = $_POST["password_label"];
    }
	//Validate email															3.EMAIL
    if(empty($_POST["email_label"])){
        $email_err = "Please enter an email.";
    } else{
		$email = $_POST['email_label'];
		$val2 = mysqli_query ($link, "SELECT `IDUtilizador` FROM `utilizador` WHERE `Email` = '$email'");
		
		if (mysqli_num_rows($val2) > 0){
			$email_err = "This email is already used.";
		} 
	} 
    // Check input errors before inserting in database
    if(empty($uname_err) && empty($password_err) && empty($confirm_password_err)){
  
		$email = $_POST['email_label'];
		$type = $_POST['tipo_label'];
		$foto = $_POST['foto_label'];
		$pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		$sql ='CALL Criar_Utilizador(:Nome,:Email,:Tipo,:Fotografia)';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':Nome', $username, PDO::PARAM_STR);
        //$stmt->bindParam(':Password', $password, PDO::PARAM_STR);
		$stmt->bindParam(':Email', $email, PDO::PARAM_STR);
		$stmt->bindParam(':Tipo', $type, PDO::PARAM_STR);
		$stmt->bindParam(':Fotografia', $foto, PDO::PARAM_STR);		
		$stmt->execute();	

		$utilizador_val = "Utilizador criado.";
    }    
    mysqli_close($link);
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monitorização de Culturas</title>
	<link href="../styles.css" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
	
	
	
</head>
<body>

<div class="topnav" id="myTopnav">
  <a href="Utilizadores.php"><i class='fas fa-users' style='font-size:20px'></i>  Utilizadores</a>
  <a href="Culturas.php"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="CriarUtilizadores.php" class="active"><i class='fas fa-user-plus' style='font-size:20px'></i>  Criar Utilizador</a>
  <a href="CriarCulturas.php"><i class='fas fa-leaf' style='font-size:20px'></i>  Criar Cultura</a>
  <a href="Medicoes.php"><i class='fas fa-ruler-combined' style='font-size:20px'></i>  Medições</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>

	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>

    <div class="wrapper">
        <h2>Dashboard Administrador</h2>
		<br>
		<br>
        <div class="form-group">
		</div>
		
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <text><b>Criar Utilizador:</b></text>
			<br>
			<br>
			<div class="form-group">
                <label>Nome</label>
                <input type="text" name="username_label" class="form-control <?php echo (!empty($uname_err)) 
? 'is-invalid' : ''; ?>" value="<?php echo $uname; ?>">
                <span class="invalid-feedback"><?php echo $uname_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password_label" class="form-control <?php echo 
(!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password_label" class="form-control <?php echo 
(!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo 
$confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
			<div class="form-group">
                <label>Email</label>
                <input type="text" name="email_label" class="form-control <?php echo (!empty($email_err)) ? 
'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
			<div class="form-group">
                <label>Foto (Opcional)</label>
                <input type="text" name="foto_label" class="form-control ">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
			<div class="form-group">
			 <label>Tipo</label>
			 <select name="tipo_label" id="tipo_label">
  <option value="investigador">Investigador</option>
  <option value="tecnico">Técnico</option>
</select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="criar_utilizador_botao" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
				<br>
				<br>
				<?php echo (!empty($utilizador_val)) ? '<div class="alert alert-success">
  
  <strong>Bem sucedido!</strong> '.$utilizador_val.'
</div>' : ''; ?>
            </div>
          <br>
		  <br>
        </form>
</body>
</html>
