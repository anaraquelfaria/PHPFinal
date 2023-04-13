<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
$cultura_remover = $remover_cultura_err = "";
 
 //Remover cultura
 if(isset($_POST['remover_cultura_botao'])) {
	 $cultura_remover = $_POST['cultura_remover_label'];
	if(empty($cultura_remover)){
		$remover_cultura_err = "Please enter a culture.";
	} else {
		$idcul = mysqli_query($link, "SELECT `IDCultura` FROM `cultura` WHERE `IDCultura` = '$cultura_remover'");
	
	if (mysqli_num_rows($idcul) == 0){
			$remover_cultura_err = "This culture is not valid";
		} else if(empty($remover_cultura_err)){
			$pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
			$sql ='CALL RemoverCultura(:ID)';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':ID', $cultura_remover, PDO::PARAM_STR);
			$stmt->execute();
			$remover_email_val = "Cultura apagada com sucesso.";
			header("Location: Culturas.php");
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
  <a href="culturas.php" class="active"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="CriarUtilizadores.php"><i class='fas fa-user-plus' style='font-size:20px'></i>  Criar Utilizador</a>
  <a href="CriarCulturas.php"><i class='fas fa-leaf' style='font-size:20px'></i>  Criar Cultura</a>
  <a href="Medicoes.php"><i class='fas fa-ruler-combined' style='font-size:20px'></i>  Medições</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>
<div class="row">
<div class="col-sm-6">
 <div class="container-fluid">
  <h2>Culturas</h2>          
  <table class="table table-hover">
    <thead>
      <tr>
		<th>ID</th>
        <th>Tipo</th>
        <th>Utilizador</th>
        <th>Zona</th>
      </tr>
    </thead>
    <tbody>
    <?php
	$result = mysqli_query ($link, "SELECT IDCultura, TipoCultura, IDUtilizador, IDZona FROM cultura");

	if ($result-> num_rows > 0){
		while($row = $result-> fetch_assoc()){ 
			$ids = $row['IDUtilizador']."<br>";
			$result2 = mysqli_query ($link, "SELECT Nome FROM utilizador WHERE IDUtilizador= '$ids'");
			while($row2 = mysqli_fetch_array($result2)) {
				
				echo "<tr><td>".$row["IDCultura"]."</td><td>".$row["TipoCultura"]."</td><td>".$row2[0]."</td><td>".$row["IDZona"]."</td></tr>";
			}
			if($row['IDUtilizador']==NULL) {
				echo "<tr><td>".$row["IDCultura"]."</td><td>".$row["TipoCultura"]."</td><td>"."NULL"."</td><td>".$row["IDZona"]."</td></tr>";
			}
		}
		echo "</tbody></table>";
	}
	?>
    </tbody>
  </table>
</div>
</div>

<div class="col-sm-2">
	</div>
	<div class="col-sm-3">
	<br><br><br><br>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<text><b>Remover Cultura:</b></text>
		   <br>
		  <br>
		<label>ID</label>
	   <input type="text" name="cultura_remover_label" class="form-control <?php echo (!empty($remover_cultura_err)) ? 'is-invalid' 
: ''; ?>" value="<?php echo $cultura_remover; ?>">
		<span class="invalid-feedback"><?php echo $remover_cultura_err; ?></span>
		<br>
		<?php echo (!empty($remover_cultura_val)) ? '<div class="alert alert-success">
  
  <strong>Bem sucedido!</strong> Cultura removida.
</div>' : ''; ?>
		
		<input type="submit" name="remover_cultura_botao" class="btn btn-danger" value="Remover">
		 
				
        </form>
		</div>
	<div class="col-sm-1">
	</div>
		
	
	</div>
</div>
  
</div>
</body>
</html>
