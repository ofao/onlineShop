<HTML>
<link rel="stylesheet" type="text/css" href="includes/css/admin.css">
<?php include "includes/header.php" ?>
	<?php if($_SESSION['user'] !== 'admin') return; ?>
	<aside style='float: left; overflow: hidden; width: 30%; margin-top: 20px; background-color: #274c78; border-radius: 20px 20px;'>
      <ol id='admin-menu'>
	<?php
		if ($conn) {
			$sql = 'SELECT table_name FROM information_schema.tables WHERE table_schema = "shop" AND table_type!="VIEW";';
			$result = mysqli_query($conn, $sql);
                
                	while ($row = mysqli_fetch_array($result)) {
        			echo "<li><a href='admin-razdel.php?table=".$row['table_name']."'>".$row['table_name']."</a></li>";
			}
		}
	?>
        <hr></hr>
        <li><a href='admin.php'><img src='https://cdn-icons-png.flaticon.com/128/876/876225.png' width=30px height=30px style='align: middle;'>Статистика</a></li>
        <li><a href='includes/scripts/exit.php'>Выход</a></li>
      </ol>
    </aside>
    <div style='width: 70%; float: right;'>
      <h1>Статистика</h1>
      <canvas id='canvas'></canvas>
      <canvas id='canvasPie'></canvas>
    </div>
</div>
<?php include "includes/footer.html" ?>
<script src='https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js'></script>
<script src='includes/js/plotting.js'></script>
</HTML>
