<?php 
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
//Consultar informações relativas ao utilizador que se logou
$info = mysqli_query($link, "SELECT * FROM `utilizador` WHERE `Nome` = '$username'");
$array= mysqli_fetch_array($info);
$id= $array['IDUtilizador'];
$tipo= $array['Tipo']; //Só para aparecer como título
$email= $array['Email'];
$fotografia= $array['Fotografia'];

  if (empty($fotografia)) {
    $fotografia="https://sapes.senai.br/assets/images/avatar-default.png";
  }

if(isset($_POST['guardar_botao'])){
      $email_novo= $_POST['email_label'];
      $fotografia_novo= $_POST['fotografia_label'];

    //Verificar se email está a branco
    if (empty($email_novo)) {

        $email_err = "Please enter an email.";
    } else {
        $val2 = mysqli_query ($link, "SELECT `IDUtilizador` FROM `utilizador` WHERE `Email` = '$email_novo'");
		    if (mysqli_num_rows($val2) > 0){
		    	$email_err = "This email is already used.";
		    } 
    }

    if(empty($uname_err) && empty($uname_err) && empty($email_err)){
          
      $pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		$sql ='CALL EditarPerfil(:Email,:Fotografia,:ID)';
		$stmt = $pdo->prepare($sql);
		
		$stmt->bindParam(':Email', $email_novo, PDO::PARAM_STR);
        $stmt->bindParam(':Fotografia', $fotografia_novo, PDO::PARAM_STR);
        $stmt->bindParam(':ID', $id, PDO::PARAM_STR);
        $stmt->execute();
        
        header("Location: Perfil.php");
    }

      
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
  <text><b>Username: </b>
  <br><br>
  <div class="form-group">
  <input readonly type="text" name="nome_label" class="form-control" value="<?php echo $username; ?>">
    </div>
  <br>
  <br>
  <text><b><i class='fas fa-envelope'></i> E-mail: </b></text>
  <br>
  <br>
  <div class="form-group">
  <input type="text" name="email_label" class="form-control <?php echo (!empty($email_err)) 
? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
<span class="invalid-feedback"><?php echo $email_err; ?></span>
    </div>
  <br>
  <br>
  <text><b>URL Fotografia: </b></text>
  <br>
  <br>
  <input type="text" name="fotografia_label" class="form-control" value="<?php echo $fotografia; ?>">
  <br>
  <br>
  <input type="submit" class="btn btn-success" name="guardar_botao" value="Guardar Alterações">
     </form>
	</div>
</div>
  </body>
</html>