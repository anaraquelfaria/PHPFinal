<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
// Define variables and initialize with empty values
$uname = $pword = $confirm_password = $email = $email_remover = $foto = $culturename = "";
$uname_err = $pword_err = $confirm_password_err = $email_err = $remover_email_val= $culture_err = $cultura_val = "";
$zona = "SELECT * FROM `zona`";
$investi = "SELECT * FROM `utilizador` WHERE `Tipo` = 'Investigador'";
$culturas = "SELECT * FROM `cultura`";
$result1 = mysqli_query($link, $investi);
$result2 = mysqli_query($link, $zona);
$result3 = mysqli_query($link, $culturas);
$options = "";
$optionsi = "";
$optionsii = "";

while($row2 = mysqli_fetch_array($result2))
{
    $options = $options."<option>$row2[0]</option>";
}
while($row1 = mysqli_fetch_array($result1))
{
    $optionsi = $optionsi."<option>$row1[1]</option>";
}
while($row3 = mysqli_fetch_array($result3))
{
    $optionsii = $optionsii."<option>$row3[0] - $row3[1]</option>";
	
}
 
 //Criar cultura
if(isset($_POST['submit_culture'])){
	$culturename =$_POST['culturename_label'];
	if(empty($culturename)){
		$culture_err = "Please enter a culture name.";
	}
	else {
		$responsavel = $_POST['responsavel_label'];
		//Descobrir id do utilizador
		$idr = mysqli_query($link, "SELECT `idutilizador` FROM `utilizador` WHERE `Nome` = '$responsavel'");	
		while ($row = $idr->fetch_assoc()) {
			$idresposavel = $row['idutilizador']."<br>";
		}
		$zonaselecionada = $_POST['zonas'];
		$pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		$sql ='CALL CriarCultura(:Tipo,:IDUtilizador,:IDZona)';  
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':Tipo', $culturename, PDO::PARAM_STR);
		$stmt->bindParam(':IDUtilizador', $idresposavel, PDO::PARAM_STR);
		$stmt->bindParam(':IDZona', $zonaselecionada, PDO::PARAM_STR);
		$stmt->execute();
		
		$cultura_val ="Cultura atribuída a ${responsavel}.";
	}	
	mysqli_close($link);

}

 //Atribuir Cultura
if(isset($_POST['atribuir_cultura_botao'])){
	$nome = $_POST['novo_responsavel_label'];
	$idu = mysqli_query($link, "SELECT `IDUtilizador` FROM `utilizador` WHERE `Nome` = '$nome'");
	while ($rowww = $idu->fetch_assoc()) {
			$idut = $rowww['IDUtilizador']."<br>";
		}
	$string= $_POST['selecionar_cultura_label'];
	$arr = explode(' ',trim($string));
	$sql = "UPDATE cultura SET IDUtilizador='$idut' WHERE IDCultura='$arr[0]'";			//$arr[1] devolve "-"
	mysqli_query($link, $sql);
	mysqli_close($link);
	
	$cultura_atr_val ="Cultura atribuída a ${nome}.";
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
  <a href="CriarUtilizadores.php"><i class='fas fa-user-plus' style='font-size:20px'></i>  Criar Utilizador</a>
  <a href="CriarCulturas.php" class="active"><i class='fas fa-leaf' style='font-size:20px'></i>  Criar Cultura</a>
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
         <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          
			<text><b>Criar Cultura:</b></text>
			<br>
			<br>  
			<div class="form-group">
                <label>Nome</label>
                <input type="text" name="culturename_label" class="form-control <?php echo (!empty($culture_err)) ? 'is-invalid' : ''; ?>"
				value="<?php echo $culturename; ?>">
                <span class="invalid-feedback"><?php echo $culture_err; ?></span>
				<br>
				<?php echo (!empty($cultura_val)) ? '<div class="alert alert-success">
  
  <strong>Bem sucedido!</strong> '.$cultura_val.'
</div>' : ''; ?>
            </div>    
          <div class="form-group">
			 <label>Investigador responsável</label>
			 <select name="responsavel_label" id="responsavel_label">
            <?php echo $optionsi;?>
        </select>
			<div class="form-group">
			 <label>Zona</label>
			   <select name="zonas" id="zonas">
            <?php echo $options;?>
        </select>
            </div>
            <div class="form-group">
                <input type="submit" name="submit_culture" class="btn btn-primary"  value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
			<br>
			<br>
			<text><b>Atribuir Cultura:</b></text>
			<br>
			<br>
			<div class="form-group">
                <label>Selecione Cultura</label>
				<select name="selecionar_cultura_label" id="selecionar_cultura_label">
					<?php echo $optionsii;?>
				</select>
				<div class="form-group">
			 <label>Novo Investigador Responsável</label>
			   <select name="novo_responsavel_label" id="novo_responsavel_label">
            <?php echo $optionsi;?>
				</select>
			</div>
			<div class="form-group">
                <input type="submit" name="atribuir_cultura_botao" class="btn btn-primary"  value="Confirmar">
				<br>
				<br>
				<?php echo (!empty($cultura_atr_val)) ? '<div class="alert alert-success">
  
  <strong>Bem sucedido!</strong> '.$cultura_atr_val.'
</div>' : ''; ?>
            </div>
			</form>
</body>
</html>
