<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");

$sensores="";
$titulo= "";

//Select ID USER
$result = mysqli_query ($link, "SELECT IDUtilizador FROM utilizador WHERE Nome = '$username'");
$row = mysqli_fetch_array ($result);
$IDUtilizador = $row[0];


$result = mysqli_query($link, "SELECT * FROM `sensor`");
while($row = mysqli_fetch_array($result)) {
    $sensores = $sensores."<option>$row[0]</option>";
}

if(isset($_POST['ver_botao'])) {
  $sensor= $_POST['selecionar_sensor_label'];
  $query = mysqli_query ($link, "SELECT * FROM sensor WHERE IDSensor = '$sensor'");
  $rows = mysqli_fetch_array ($query);
  $lim_inf= $rows[2];
  $lim_sup=$rows[3];
 
  if($sensor=='H1'){
    $titulo= "Humidade";
    echo "Não há mais dados disponíveis";
  }
  if($sensor=='L1'){
    $titulo= "Luminosidade";
    echo "Não há mais dados disponíveis";
  }
  if($sensor=='T1'){
    $titulo= "Temperatura";
  }
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
  <a href="Medicoes.php" class="active"><i class='fas fa-ruler-combined' style='font-size:20px'></i>  Medições</a>
  <a href="alertas.php" ><i class='glyphicon glyphicon-bell' style='font-size:20px'></i>  Alertas</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>
	
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(tempChart);

      function tempChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data', 'Temperatura', 'Limite Min Sensor', 'Limite Max Sensor',],
         <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = 'T1'");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."'], ['".$lim_inf."'], ['".$lim_sup."']],";
                }
              }        
         ?>
        ]);
        var options = {
          title: 'Temperatura',
          legend: { position: 'bottom' },
          series: {
            0: { color: '#2960d6' },
            1: { color: '#d41515' },
            2: { color: '#d41515' },
            3: { color: '#f5d742' },
            4: { color: '#f5d742' },
           }, 
          vAxis: {
            title: 'Graus'
          },
          hAxis: {
               title :'Data',
              }
        };
        var chart = new google.visualization.LineChart(document.getElementById('Temp_chart_div'));
        chart.draw(data, options);
      }  

    </script>
	
</head>
<body>


<br>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
<div class="row">
  <div class= "col-sm-1"></div>
    <div class="col-sm-6">
    <div class="form-group">
                <label>Sensor</label>
				<select name="selecionar_sensor_label" id="selecionar_sensor_label">
					<?php echo $sensores;?>
				</select>
        <div class="form-group">
                <input type="submit" name="ver_botao" class="btn btn-primary"  value="Ver">
</div>

  <div class="row">
    <div class= "col-sm-1"></div>
    <div class="col-sm-6">
        <div class="container-fluid">

         <div id="Temp_chart_div" style="width: 900px; height: 500px"></div>     
      </div>
    </div>


  </div>
</form>
</body>
</html>
