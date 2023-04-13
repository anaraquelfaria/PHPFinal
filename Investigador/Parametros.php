<?php
// Include config file
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
$culturas = "SELECT *
FROM cultura, utilizador
WHERE cultura.IDUtilizador=utilizador.IDUtilizador 
AND utilizador.Nome='$username'";
$result3 = mysqli_query($link, $culturas);
$optionsii = "";
$idoptionsii ="";
$idcultura="";
$tempmin= $tempmax =$luzmin= $luzmax= $hummin= $hummax= $martemp= $marluz= $marhum= $timeram= $timerver= "";


	while($row3 = mysqli_fetch_array($result3)) {
		$optionsii = $optionsii."<option>$row3[0] - $row3[1]</option>"; 
	}
	
	
	
	
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
  <a href="Parametros.php" class="active"><i class='glyphicon glyphicon-list-alt' style='font-size:20px'></i>  Parâmetros Cultura</a>
  <a href="Medicoes.php"><i class='glyphicon glyphicon-search' style='font-size:20px'></i>  Medições</a>
  <a href="alertas.php"><i class='glyphicon glyphicon-bell' style='font-size:20px'></i>  Alertas</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>
	
</head>
<body>


    <div class="wrapper">
       
        <div class="form-group">
		<text><b>Consultar Cultura:</b></text>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		 <select name="option" id="option">
            <?php echo $optionsii;?>
        </select>
  <input type="submit" name="parametros_botao" class="btn btn-primary" value="Mostrar Parâmetros">
				</div>
	<?php
	//Mostrar parametros - Vai autopreencher as labels com os valores atuais, que posteriormente podem ser modificados
	
	if(isset($_POST['parametros_botao'])) { 
		$variavel= $_POST['option'];
		$arr= explode(' ',trim($variavel));		//arr[0] é o id da label de selecionar cultura
		$idcultura= $arr[0];
	
		$parametros = mysqli_query($link, "SELECT IDCultura, TemperaturaMin, TemperaturaMax, LuzMin, LuzMax, HumidadeMin, HumidadeMax, MargemPerigosaTemp, MargemPerigosaLuz, MargemPerigosaHum, AlertaAmareloTimer, AlertaVermelhoTimer
		 FROM `parametrocultura` 
		 WHERE `IDCultura` = '$idcultura'");		//A que ID corresponde a variável?
			while ($row = $parametros->fetch_assoc()) {
				$idcultura= $row['IDCultura'];
				$tempmin= $row['TemperaturaMin'];
				$tempmax= $row['TemperaturaMax'];
				$luzmin= $row['LuzMin'];
				$luzmax= $row['LuzMax'];
				$hummin= $row['HumidadeMin'];
				$hummax= $row['HumidadeMax'];
				$martemp= $row['MargemPerigosaTemp'];
				$marluz= $row['MargemPerigosaLuz'];
				$marhum= $row['MargemPerigosaHum'];
				$timeram= $row['AlertaAmareloTimer'];
				$timerver= $row['AlertaVermelhoTimer'];
			}
		}
	?>

				<br>
				<div class="form-group">
                <label>ID Cultura sob consulta</label>
                <input readonly type="text" name="id_label" class="form-control" value="<?php echo $idcultura; ?>">
				</div>
		<br>
			<br>
			<div class="form-group">
                <label>Temperatura Mínima</label>
                <input type="text" name="tempmin_label" class="form-control" value="<?php echo $tempmin; ?>">
                <span class="invalid-feedback"><?php echo $tempmin_err; ?></span>
            </div>   
<div class="form-group">
                <label>Temperatura Máxima</label>
                <input type="text" name="tempmax_label" class="form-control" value="<?php echo $tempmax; ?>">
                <span class="invalid-feedback"><?php echo $tempmax_err; ?></span>
            </div>    
			<div class="form-group">
				<label>Luz Mínima</label>
                <input type="text" name="luzmin_label" class="form-control" value="<?php echo $luzmin; ?>">
                <span class="invalid-feedback"><?php echo $luzmin_err; ?></span>
            </div>   
<div class="form-group">
                <label>Luz Máxima</label>
                <input type="text" name="luzmax_label" class="form-control" value="<?php echo $luzmax; ?>">
                <span class="invalid-feedback"><?php echo $luzmax_err; ?></span>
            </div>  
<div class="form-group">
                <label>Humidade Mínima</label>
                <input type="text" name="hummin_label" class="form-control <?php echo (!empty($hummin_err)) 
? 'is-invalid' : ''; ?>" value="<?php echo $hummin; ?>">
                <span class="invalid-feedback"><?php echo $hummin_err; ?></span>
            </div>    	
			<div class="form-group">
                <label>Humidade Máxima</label>
                <input type="text" name="hummax_label" class="form-control <?php echo (!empty($hummax_err)) 
? 'is-invalid' : ''; ?>" value="<?php echo $hummax; ?>">
                <span class="invalid-feedback"><?php echo $hummax_err; ?></span>
            </div>   
			<div class="form-group">
                <label>Margem Perigosa Temperatura</label>
                <input type="text" name="martemp_label" class="form-control <?php echo (!empty($martemp_err)) 
? 'is-invalid' : ''; ?>" value="<?php echo $martemp; ?>">
                <span class="invalid-feedback"><?php echo $martemp_err; ?></span>
            </div>    	
			<div class="form-group">
                <label>Margem Perigosa Luz</label>
                <input type="text" name="marluz_label" class="form-control <?php echo (!empty($marluz_err)) 
? 'is-invalid' : ''; ?>" value="<?php echo $marluz; ?>">
                <span class="invalid-feedback"><?php echo $marluz_err; ?></span>
            </div>    		
			<div class="form-group">
                <label>Margem Perigosa Humidade</label>
                <input type="text" name="marhum_label" class="form-control <?php echo (!empty($marhum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $marhum; ?>">
                <span class="invalid-feedback"><?php echo $marhum_err; ?></span>
            </div> 
			<div class="form-group">
                <label>Timer Alerta Amarelo </label>
                <input type="text" name="timeram_label" class="form-control <?php echo (!empty($timeram_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $timeram; ?>">
                <span class="invalid-feedback"><?php echo $timeram_err; ?></span>
            </div> 
			<div class="form-group">
                <label>Timer Alerta Vermelho</label>
                <input type="text" name="timerver_label" class="form-control <?php echo (!empty($timerver_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $timerver; ?>">
                <span class="invalid-feedback"><?php echo $timerver_err; ?></span>
            </div> 
			  <input type="submit" class="btn btn-success" name="guardar_botao" value="Guardar">			
	<?php 
	
	if(isset($_POST['guardar_botao'])) {
		
		
		$idcultura = $_POST['id_label'];
		$tempmin = $_POST['tempmin_label'];
		$tempmax = $_POST['tempmax_label'];
		$luzmin = $_POST['luzmin_label'];
		$luzmax = $_POST['luzmax_label'];
		$hummin = $_POST['hummin_label'];
		$hummax = $_POST['hummax_label'];
		$martemp = $_POST['martemp_label'];
		$marluz = $_POST['marluz_label'];
		$marhum = $_POST['marhum_label'];
		$timeram = $_POST['timeram_label'];
		$timerver = $_POST['timerver_label'];
	
		  $pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		  $sql ='CALL EditarParametros(:ID,:TempMin,:TempMax,:LMin,:LMax,:HumMin,:HumMax,:MargemTemp,:MargemLuz,:MargemHum,:AlertaAmareloTimer,:AlertaVermelhoTimer)';
		  $stmt = $pdo->prepare($sql);
		  $stmt->bindParam(':ID', $idcultura, PDO::PARAM_STR);
		  $stmt->bindParam(':TempMin', $tempmin, PDO::PARAM_STR);
		  $stmt->bindParam(':TempMax', $tempmax, PDO::PARAM_STR);
		  $stmt->bindParam(':LMin', $luzmin, PDO::PARAM_STR);
		  $stmt->bindParam(':LMax', $luzmax, PDO::PARAM_STR);	
		  $stmt->bindParam(':HumMin', $hummin, PDO::PARAM_STR);
		  $stmt->bindParam(':HumMax', $hummax, PDO::PARAM_STR);
		  $stmt->bindParam(':MargemTemp', $martemp, PDO::PARAM_STR);
		  $stmt->bindParam(':MargemLuz', $marluz, PDO::PARAM_STR);
		  $stmt->bindParam(':MargemHum', $marhum, PDO::PARAM_STR);
		  $stmt->bindParam(':AlertaAmareloTimer', $timeram, PDO::PARAM_STR);
		  $stmt->bindParam(':AlertaVermelhoTimer', $timerver, PDO::PARAM_STR);
		  $stmt->execute();
	
	}
	?>
</body>
<html>			