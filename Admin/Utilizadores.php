<?php
// Include config file
//require_once "./config.php";
session_start();
$uname = $pword = $confirm_pword = $email = $email_remover = $foto = $culturename = $estado = "";
$uname_err = $pword_err = $confirm_pword_err = $email_err = $remover_email_val= $culture_err = ""; 
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
//REMOVER USER
if(isset($_POST['remover_email_botao'])){
	$email_remover = $_POST['email_remover_label'];
	if(empty($email_remover)){
		$remover_email_err = "Please enter an email.";
	} else {
		$val3 = mysqli_query ($link, "SELECT `IDUtilizador` FROM `utilizador` WHERE `Email` = '$email_remover'");
		
		if (mysqli_num_rows($val3) == 0){
			$remover_email_err = "This email is not valid";
		} else if(empty($remover_email_err)){
		$pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		$sql ='CALL Remover_Utilizador(:Email)';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':Email', $email_remover, PDO::PARAM_STR);
		$stmt->execute();
		$remover_email_val = "Utilizador apagado com sucesso.";
		header("Location: Utilizadores.php");
		}
	} 
	
	mysqli_close($link);
}
 
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dash</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> 
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link href="../styles.css" rel="stylesheet">
	
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
	
	
	
</head>
<body>

<div class="topnav" id="myTopnav">
  <a href="Utilizadores.php" class="active"><i class='fas fa-users' style='font-size:20px'></i>  Utilizadores</a>
  <a href="culturas.php"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="CriarUtilizadores.php"><i class='fas fa-user-plus' style='font-size:20px'></i>  Criar Utilizador</a>
  <a href="CriarCulturas.php"><i class='fas fa-leaf' style='font-size:20px'></i>  Criar Cultura</a>
  <a href="Medicoes.php"><i class='fas fa-ruler-combined' style='font-size:20px'></i>  Medições</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i>  Logout</a>
		</div>
</div>
<div class="row">
   <div class="col-sm-6">
	<div class="container-fluid">
  <h2>Utilizadores</h2> 
  <br>
  <div class="dropdown">
  <button class="btn btn-primary dropdown" name="filtro" type="button" data-toggle="dropdown" > Mostrar Todos 
  <span class="glyphicon glyphicon-sort-by-attributes-alt"></span></button>
  <ul class="dropdown-menu">
    <li><a href="Filtro/Investigadores.php">Investigador</a></li>
    <li><a href="Filtro/Tecnicos.php">Técnico</a></li>
  </ul>
</div>

<br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
  <table class="table table-hover">
    <thead>
      <tr>
		<th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Tipo</th>
      </tr>
    </thead>
    <tbody>
    <?php
	//Mostrar todos
	$result = mysqli_query ($link, "SELECT IDUtilizador, Nome, Email, Tipo FROM utilizador");
	if ($result-> num_rows > 0){
		while($row = $result-> fetch_assoc()){
			echo "<tr><td>".$row["IDUtilizador"]."</td><td>".$row["Nome"]."</td><td>".$row["Email"]."</td><td>".$row["Tipo"]."</td></tr>";
		}
		echo "</tbody></table>";
		mysqli_close($link);
	}
	?>
    </tbody>
  </table>
  </form>
</div>
</div>
	<div class="col-sm-2">
	</div>
	<div class="col-sm-3">
	<br><br><br><br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<text><b>Remover Utilizador:</b></text>
		   <br>
		  <br>
		<label>Email</label>
	   <input type="text" name="email_remover_label" class="form-control <?php echo (!empty($remover_email_err)) ? 'is-invalid' 
: ''; ?>" value="<?php echo $email_remover; ?>">
		<span class="invalid-feedback"><?php echo $remover_email_err; ?></span>
		<br>
		<?php echo (!empty($remover_email_val)) ? '<div class="alert alert-success">
  
  <strong>Bem sucedido!</strong> Utilizador removido.
</div>' : ''; ?>
		
		<input type="submit" name="remover_email_botao" class="btn btn-danger" value="Remover">
		 
				
        </form>
		</div>
	<div class="col-sm-1">
	</div>
		
	
	</div>
</div>
  
</div>

</body>
</html>
