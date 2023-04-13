<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");
 
 
$uname = $pword = $confirm_password = $email = $email_remover = $foto = $culturename = $estado = "";
$uname_err = $pword_err = $confirm_password_err = $email_err = $remover_email_val= $culture_err = "";
$zonasoptions = ""; 
$zone= "";
$timeframe ="";
$time_30min= $time_1dia= $time_max="";

$resultzonas = mysqli_query($link,  "SELECT * FROM `zona`");
while($rowid = mysqli_fetch_array($resultzonas))
{
    $zonasoptions = $zonasoptions."<option>$rowid[0]";
}
 

if (isset($_POST['consultar'])){
$zone= $_POST['zona_label'];
$sensor_arr = substr(strval($zone), -1);
$sensor_temp = "T".$sensor_arr;
$sensor_luz = "L".$sensor_arr;
$sensor_hum = "H".$sensor_arr;

$timeframe = $_POST['timeframe_label'];
if ($timeframe == "30 min"){
  $time = $time_30min;
}
if ($timeframe == "1 dia"){
  $time = $time_1dia;
}
if ($timeframe == "Máx"){
  $time = $time_max;
}

}


//Limpar Medições
	if(isset($_POST['limpar_medicao_botao'])){
		$pdo = new PDO("mysql:host=localhost;dbname=g10", $username, $password);
		$sql = 'CALL LimparMedicoes()';
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
	}

//Opções de timeframes
$timeseconds = time();
$timeseconds_30min = $timeseconds - 1800;
$time_30min = strval(date('Y-m-d h:i:s', $timeseconds_30min));

$timeseconds_1dia = $timeseconds - 86400;
$time_1dia = strval(date('Y-m-d h:i:s', $timeseconds_1dia));

$time_max = '2000-05-15 23:30:26';

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
	
	 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(tempChart);

      function tempChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data','Leitura'],
         <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = '$sensor_temp' AND Data_e_Hora > '$time' ");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."']],";
                }
              }        
         ?>
        ]);
        var options = {
          title: 'Temperatura',
          legend: { position: 'bottom' },
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

      google.charts.setOnLoadCallback(humChart);

      function humChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data','Leitura'],
         <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = '$sensor_hum' AND Data_e_Hora > '$time' ");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."']],";
                }
              }        
         ?>
        ]);
        var options = {
          title: 'Humidade',
          legend: { position: 'bottom' },
          vAxis: {
            title: 'Volume'
          },
          hAxis: {
               title :'Data',
              }
        };
        var chart = new google.visualization.LineChart(document.getElementById('Hum_chart_div'));
        chart.draw(data, options);
      }

      google.charts.setOnLoadCallback(lumChart);

      function lumChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data','Leitura'],
        <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = '$sensor_luz' AND Data_e_Hora > '$time' ");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."']],";
                }
              }        
        ?>
        ]);
        var options = {
          title: 'Luminosidade',
          legend: { position: 'bottom' },
          vAxis: {
            title: 'Luz %'
          },
          hAxis: {
               title :'Data',
              }
        };
        var chart = new google.visualization.LineChart(document.getElementById('Lum_chart_div'));
        chart.draw(data, options);
      }  
    </script>
	
</head>
<body>

<div class="topnav" id="myTopnav">
  <a href="Utilizadores.php"><i class='fas fa-users' style='font-size:20px'></i>  Utilizadores</a>
  <a href="culturas.php"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="CriarUtilizadores.php"><i class='fas fa-user-plus' style='font-size:20px'></i>  Criar Utilizador</a>
  <a href="CriarCulturas.php"><i class='fas fa-leaf' style='font-size:20px'></i>  Criar Cultura</a>
  <a href="Medicoes.php" class="active"><i class='fas fa-ruler-combined' style='font-size:20px'></i>  Medições</a>
  <a href="javascript:void(0);" class="icon" onclick="myFunction()">
    <i class="fa fa-bars"></i>
	<div class= "topnav-right">
	<a href="Perfil.php"><i class='glyphicon glyphicon-user' style='font-size:20px'></i>  Perfil</a>
	<a href="../Login/login.php"><i class='glyphicon glyphicon-log-out' style='font-size:20px'></i> Logout</a>
		</div>
</div>
<br>
<br>
<form method="post">
<div class="form-group">
                <label>Zona</label>
				<select name="zona_label" id="zona_label">
					<?php echo $zonasoptions;?>
				</select>
        </div>
<div class="form-group">
  <label>Timeframe</label>
	<select name="timeframe_label" id="timeframe_label">
		<option> 30 min <option> 1 dia <option> Máx
	</select>
</div>
<div class="form-group">
  <input type="submit" name="consultar" class="btn btn-primary"  value="Consultar">
</div>
<div class="col-sm-7">
<div class="form-group">
<text><b>Gráficos sob consulta: </b>
  <input readonly type="text" name="consulta_label" class="form-control" value="<?php echo $zone." - ".$timeframe; ?>">
    </div>


<div class="row">
  <div class="column">
  <div class="card">
	  <div class="container-fluid">
       <div class="card-body"></div>
        <div id="Temp_chart_div" style="width: 900px; height: 500px"></div><br>
      
      </div>
  </div>
  <div class="card">
	  <div class="container-fluid">
       <div class="card-body"></div>
        <div id="Hum_chart_div" style="width: 900px; height: 500px"></div><br>
      
      </div>
  </div>
  </div>
  <div class="card">
	  <div class="container-fluid">
       <div class="card-body"></div>
        <div id="Lum_chart_div" style="width: 900px; height: 500px"></div><br>
        <br>
        <text><b> Limpar Medições:</b></text>
        <input type="submit" class="btn btn-danger" name="limpar_medicao_botao" value="Limpar">
        <br>
        <br>
      </div>
  </div>
  <br>
	</form>
</div>
<br>
</body>
</html>
