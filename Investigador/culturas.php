<?php
// Include config file
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
$culturasoptions = "";
$result1 = mysqli_query ($link, "SELECT  IDCultura, TipoCultura, Estado, IDZona 
FROM cultura, utilizador
WHERE cultura.IDUtilizador=utilizador.IDUtilizador 
AND utilizador.Nome='$username'
");
while($row1 = mysqli_fetch_array($result1) ){
	$culturasoptions = $culturasoptions."<option>$row1[0] - $row1[1]</option>"; 
}



if(isset($_POST['mudar_nome_cultura_botao'])){
	$variavel= $_POST['cultura_label'];
		$arr= explode(' ',trim($variavel));		
		$IDCultura_new= $arr[0];
		$TipoCultura= $_POST['novo_nome_label'];
	
	$pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		  $sql ='CALL AlterarTipoCultura(:IDCultura,:TipoCultura)';
		  $stmt = $pdo->prepare($sql);
		  $stmt->bindParam(':IDCultura', $IDCultura_new, PDO::PARAM_STR);
		  $stmt->bindParam(':TipoCultura', $TipoCultura, PDO::PARAM_STR);
		  $stmt->execute();
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
<a href="culturas.php" class="active"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="Parametros.php"><i class='glyphicon glyphicon-list-alt' style='font-size:20px'></i>  Parâmetros Cultura</a>
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
</div>
<div class="row">
<div class="col-sm-7">
 <div class="container-fluid">
  <h2>Culturas</h2>          
  <table class="table table-hover">
    <thead>
      <tr>
		
        <th>Tipo</th>
		<th>Estado</th>
        <th>Zona</th>
      </tr>
    </thead>
 
    <tbody>
    <?php
	
	//Consultar apenas as suas culturas (AINDA FALTA)
	$result = mysqli_query ($link, "SELECT  TipoCultura, Estado, IDZona 
	FROM cultura, utilizador
	WHERE cultura.IDUtilizador=utilizador.IDUtilizador 
	AND utilizador.Nome='$username'
	");

	if ($result-> num_rows > 0){
		while($row = $result-> fetch_assoc()){ 
				echo "<tr><td>".$row["TipoCultura"]."</td><td>".$row["Estado"]."</td><td>".$row["IDZona"]."</td><td>";
				
		}
		echo "</tbody></table>";
	
	}


	?>
    </tbody>
  </table>
</div>

<div class="col-sm-3">
<div class="form-group">
			 <label>Mudar Nome da Cultura:</label>
			 <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			   <select name="cultura_label" id="cultura_label">
            <?php echo $culturasoptions;?>
				</select>
				<br>
				<br>
				<div class="form-group">
                <label>Novo Nome: </label>
                <input type="text" name="novo_nome_label" class="form-control">
            </div>  
			</div>
			<div class="form-group">
                <input type="submit" name="mudar_nome_cultura_botao" class="btn btn-primary"  value="Confirmar">
				<br>
</div>

</div>


	
	</div>
</div>
  
</div>
</body>
</html>
