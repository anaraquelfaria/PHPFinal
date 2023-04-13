<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
$sensor_alerta="";

?>
 

 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Investigador</title>
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
	
    <div class="topnav" id="myTopnav">
  <a href="Medicoes.php"><i class='fas fa-ruler-combined' style='font-size:20px'></i>  Medições</a>
  <a href="alertas.php" class="active"><i class='glyphicon glyphicon-bell' style='font-size:20px'></i>  Alertas</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>
	
</head>
<body>
<br>
<div class="row">

<div class="col-sm-6">
 <div class="container-fluid">
<?php 

  //Extrapolar todos os dados do alertasensor
  $infoalerta= mysqli_query($link, "SELECT * FROM alertasensor");

  if(mysqli_num_rows($infoalerta) > 0) {
    while($linha = $infoalerta-> fetch_assoc()) {

    $idsensor_alerta= $linha['IDSensor'];
    $medicao_alerta= $linha['Medicao'];
    $hora_alerta=$linha['Hora_Escrita'];
    
    $letrasensor = $idsensor_alerta[0];  

    if($letrasensor =="T"){
      $sensor_alerta= "temperatura";
    }
    if($letrasensor=='L'){
      $sensor_alerta= "luminosidade";
    }
    if($letrasensor=='H'){
      $sensor_alerta= "humidade";
    }


    $mensagem= "O sensor ".$idsensor_alerta." atingiu ".$medicao_alerta." de ".$sensor_alerta.".";
    //Imprimir aviso        
        echo '<div class="alert alert-danger">
  
  <strong>Alerta Sensor!</strong> '.$hora_alerta.': '.$mensagem.'
</div>';
      
    }
  }

?>
</div>
</div>

</body>
</html>





