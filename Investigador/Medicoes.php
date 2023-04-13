<?php
session_start();
$username = $_SESSION['username'];
$password = $_SESSION['password'];
$link = mysqli_connect("localhost", $username, $password, "g10");

$timeframe ="";
$culture_name="";
//Select ID USER
$result = mysqli_query ($link, "SELECT IDUtilizador FROM utilizador WHERE Nome = '$username'");
$row = mysqli_fetch_array ($result);
$IDUtilizador = $row[0];

//Select cultura
$result = mysqli_query ($link, "SELECT * FROM cultura WHERE IDUtilizador = '$IDUtilizador'");

$options = "";
while($row = mysqli_fetch_array($result))
{
    $options = $options."<option> $row[0] - $row[1]</option>";

}
$IDCulture = "";
if(isset($_POST['submit'])){
  $selectedculture_arr = $_POST['selecionar_cultura_label'];
  $selectedculture = explode(' ', trim($selectedculture_arr));
  $IDCulture = $selectedculture[0];
  $culture_name = $selectedculture[2];
  }
//Select Zone and Sensor
$result = mysqli_query ($link, "SELECT * FROM cultura WHERE IDCultura = '$IDCulture'");
$row = mysqli_fetch_array($result);
$zone = $row['IDZona']; 
$sensor_arr = substr(strval($zone), -1);
$sensor_temp = "T".$sensor_arr;
$sensor_luz = "L".$sensor_arr;
$sensor_hum = "H".$sensor_arr;

//Select param

  $result = mysqli_query ($link, "SELECT * FROM parametrocultura WHERE IDCultura = '$IDCulture'");
  $row = mysqli_fetch_array($result);
    $tempmin = $row[1];
    $tempmax =  $row[2];
    $luzmin =  $row[3];
    $luzmax = $row[4];
    $hummin = $row[5];
    $hummax =  $row[6];
    $martemp =  $row[7];
    $marluz =  $row[8];
    $marhum =  $row[9];
    $temp_amarelomin = $tempmin + $martemp;
    $temp_amarelomax = $tempmax - $martemp;
    $luz_amarelomin = $luzmin + $marluz;
    $luz_amarelomax = $luzmax - $marluz;
    $hum_amarelomin = $hummin + $marhum;
    $hum_amarelomax = $hummax - $marhum;

//Time
$timeseconds = time();

$timeseconds_30min = $timeseconds - 1800;
$time_30min = strval(date('Y-m-d h:i:s', $timeseconds_30min));
$timeseconds_1h = $timeseconds - 3600;
$time_1h = strval(date('Y-m-d h:i:s', $timeseconds_1h));
$timeseconds_1d = $timeseconds - 86400;
$time_1dia = strval(date('Y-m-d h:i:s', $timeseconds_1d));
$time_max = "2000-05-15 23:30:17";


if (isset($_POST['submit'])){
$timeframe = $_POST['timeframe_label'];
if ($timeframe == "30 min"){
  $time = $time_30min;
}
if ($timeframe == "1 hora"){
  $time = $time_1h;
}
if ($timeframe == "1 dia"){
  $time = $time_1dia;
}
if ($timeframe == "Máx"){
  $time = $time_max;
}
}
?>

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

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(tempChart);

      function tempChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data','Temperatura', 'Min', 'Max', 'Margem Min', 'Margem Max',],
         <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = '$sensor_temp' AND Data_e_Hora > '$time' ");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."'], ['".$tempmin."'], ['".$tempmax."'], ['".$temp_amarelomin."'], ['".$temp_amarelomax."']],";
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

      google.charts.setOnLoadCallback(humChart);

      function humChart() {
        var data = google.visualization.arrayToDataTable([
          ['Data','Leitura', 'Min', 'Max', 'Margem Min', 'Margem Max',],
         <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = '$sensor_hum' AND Data_e_Hora > '$time' ");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."'], ['".$tempmin."'], ['".$tempmax."'], ['".$temp_amarelomin."'], ['".$temp_amarelomax."']],";
                }
              }        
         ?>
        ]);
        var options = {
          title: 'Humidade',
          legend: { position: 'bottom' },
          series: {
            0: { color: '#2960d6' },
            1: { color: '#d41515' },
            2: { color: '#d41515' },
            3: { color: '#f5d742' },
            4: { color: '#f5d742' },
           }, 
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
          ['Data','Leitura', 'Min', 'Max', 'Margem Min', 'Margem Max',],
        <?php 
              $result = mysqli_query ($link, "SELECT Data_e_Hora, Leitura FROM medicao WHERE Sensor = '$sensor_luz' AND Data_e_Hora > '$time' ");
              if($row = mysqli_num_rows($result)>0 ){
                while($row = $result-> fetch_assoc()){
                  echo "['".$row['Data_e_Hora']."', ['".$row['Leitura']."'], ['".$tempmin."'], ['".$tempmax."'], ['".$temp_amarelomin."'], ['".$temp_amarelomax."']],";
                }
              }        
        ?>
        ]);
        var options = {
          title: 'Luminosidade',
          legend: { position: 'bottom' },
          series: {
            0: { color: '#2960d6' },
            1: { color: '#d41515' },
            2: { color: '#d41515' },
            3: { color: '#f5d742' },
            4: { color: '#f5d742' },
           }, 
          vAxis: {
            title: 'Luz %',
          },
          hAxis: {
               title :'Data',
              }
        };
        var chart = new google.visualization.LineChart(document.getElementById('Lum_chart_div'));
        chart.draw(data, options);
      }  
    </script>

  <div class="topnav" id="myTopnav">
  <a href="culturas.php"><i class='fas fa-seedling' style='font-size:20px'></i>  Culturas</a>
  <a href="Parametros.php"><i class='glyphicon glyphicon-list-alt' style='font-size:20px'></i>  Parâmetros Cultura</a>
  <a href="Medicoes.php" class="active"><i class='glyphicon glyphicon-search' style='font-size:20px'></i>  Medições</a>
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
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
    <br>
      <div class="form-group">
      <select name="timeframe_label" id="timeframe_label">
					<option>30 min<option>1 hora<option>1 dia<option>Máx
				</select>
        <select name="selecionar_cultura_label" id="selecionar_cultura_label">
					<?php echo $options;?>
				</select>
        <input type="submit" name="submit" value="Consultar">
      </div>
      <div class="col-sm-7">
<div class="form-group">
<text><b>Gráficos sob consulta: </b>
  <input readonly type="text" name="consulta_label" class="form-control" value="<?php echo $culture_name." - ".$timeframe; ?>">
    </div>
  </select>
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
      </div>
  </div>
  </form>
</body>
</html>
