<?php 
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
//Consultar informações relativas ao utilizador que se logou
$info = mysqli_query($link, "SELECT * FROM `utilizador` WHERE `Nome` = '$username'");
$array= mysqli_fetch_array($info);
$tipo= $array['Tipo']; //Só para aparecer como título
$email= $array['Email'];
$fotografia= $array['Fotografia'];

  if (empty($fotografia)) {
    $fotografia="https://sapes.senai.br/assets/images/avatar-default.png";
  }

  if(isset($_POST['editar_botao'])){
  header("Location: EditarPerfil.php");
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
<a href="culturas.php"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="Parametros.php"><i class='glyphicon glyphicon-list-alt' style='font-size:20px'></i>  Parâmetros Cultura</a>
  <a href="Medicoes.php"><i class='glyphicon glyphicon-search' style='font-size:20px'></i>  Medições</a>
  <a href="alertas.php"><i class='glyphicon glyphicon-bell' style='font-size:20px'></i>  Alertas</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php" class="active"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>


<div class="row">
<div class="col-sm-1">
</div>
<div class="col-sm-2">
 <div class="container-fluid">
 <br>
  <br>
  <br>
  <br>
  <br>
  <img src=<?php echo $fotografia; ?>  width="200" >
     
  

  </div>
  </div>
  <div class="col-sm-6">
  <br>
  <br>
  <br>
  <br>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
  <h2>Investigador</h2>  
  <br>
  <br>        
  <text><b>Username: </b><?php echo $username; ?> </text>
  <br>
  <br>
  <text><b><i class='fas fa-envelope'></i> E-mail: </b><?php echo $email; ?></text>
  <br>
  <br>
  <i class='fas fa-pen' style='font-size:20px'></i><input  type="submit" name="editar_botao" class="btn"  value= "Editar perfil">
     </form>
	</div>
</div>
  </body>
</html>