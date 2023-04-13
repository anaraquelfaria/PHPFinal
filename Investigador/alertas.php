<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
$sensor_alerta="";

//Saber ID de quem se logou
$info = mysqli_query($link, "SELECT * FROM `utilizador` WHERE `Nome` = '$username'");
$array= mysqli_fetch_array($info);
$idusername= $array['IDUtilizador']; 

//Saber todos as culturas que me pertencem
$result = mysqli_query ($link, "SELECT IDCultura FROM cultura WHERE IDUtilizador = '$idusername'");

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
<a href="culturas.php"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="Parametros.php"><i class='glyphicon glyphicon-list-alt' style='font-size:20px'></i>  Parâmetros Cultura</a>
  <a href="Medicoes.php"><i class='glyphicon glyphicon-search' style='font-size:20px'></i>  Medições</a>
  <a href="alertas.php" class="active" class="active"><i class='glyphicon glyphicon-bell' style='font-size:20px'></i>  Alertas</a>
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
//Ciclo para verificar se as minhas culturas estão em alerta
while($ids = mysqli_fetch_array($result)) { //Percorre cada cultura
  $idcultura = $ids[0];

  //Extrapolar todos os dados do alertacultura e se um dos ID's corresponder a uma das minhas culturas emitir aviso
  $infoalerta= mysqli_query($link, "SELECT * FROM alertacultura WHERE IDCultura='$idcultura'");

  if(mysqli_num_rows($infoalerta) > 0) {
    while($linha = $infoalerta-> fetch_assoc()) {

    $idcultura_alerta= $linha['IDCultura'];

    //Consultar zona em que a cultura está inserida
    $consult= mysqli_query($link, "SELECT * FROM cultura WHERE IDCultura='$idcultura'");
    $arrayconsult= $consult-> fetch_assoc(); // mysqli_fetch_array($consult);
    $zona= $arrayconsult['IDZona'];
    $idzona= explode('Z',trim($zona)); 
    $nr_zona = $idzona[1];

    $medicao_alerta= $linha['Medicao'];
    $tipo_alerta=$linha['isAlertaVermelho'];
    $hora_alerta=$linha['HoraEscrita'];
    $idsensor=$linha['IDSensor'];
    $sensor = explode($nr_zona, trim($idsensor));  
    $letrasensor = $sensor[0];
    if($letrasensor =="T"){
      $sensor_alerta= "temperatura";
    }
    if($letrasensor=='L'){
      $sensor_alerta= "luminosidade";
    }
    if($letrasensor=='H'){
      $sensor_alerta= "humidade";
    }


    $mensagem= "A sua cultura ".$idcultura_alerta." atingiu ".$medicao_alerta." de ".$sensor_alerta.".";
    //Imprimir aviso
      if($tipo_alerta==0){
        
        echo '<div class="alert alert-warning">
  
        <strong>Alerta Amarelo!</strong> '.$hora_alerta.': '.$mensagem.'
      </div>';
      }
      if($tipo_alerta==1) {
        echo '<div class="alert alert-danger">
  
  <strong>Alerta Vermelho!</strong> '.$hora_alerta.': '.$mensagem.'
</div>';
      }
    }
  }
}
?>
</div>
</div>

</body>
</html>





