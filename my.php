<HTML>
<?php include "includes/header.php" ?>
<?php
if( $conn ) {
  $sql = "SELECT phone, id, FIO FROM buyer WHERE id=".$_SESSION['user'].";";

  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_array($result)) { 
    $name = $row['FIO'];
    $phone = $row['phone'];
  }
}
?>
<link rel="stylesheet" type="text/css" href="includes/css/my.css">
	<section style='display: flex; margin: 20px; justify-content: space-between;'><section style='display:flex; align-items: center;'>
		<img src='pictures/user.svg' height='100px' width='100px'>
    <section style='display: flex; flex-direction: column;'>
      <h3><?= $name; ?></h3>
      <h3 style='color: grey;'><?= $phone; ?></h3>
    </section></section>
    <form method='post' action='includes/scripts/exit.php' style='position: relative; display: flex; gap: 10px;'>
    <a href='settings.php' id='settings' class='user-button'><img src='pictures/settings.svg' class='user-button'></a>
    <button type='submit' id='settings'><img src='pictures/exit.svg' class='user-button'></button>
    </form></section>
  <section style='display: flex; flex-wrap: wrap; justify-content: space-around;'>
    <div class='cards_my' style="cursor: pointer;" onclick="window.location='basket.php';">
      <img src='https://cdn-icons-png.flaticon.com/128/5392/5392794.png'>
      <h2>Корзина</h2>
    </div>
    <div class='cards_my' style="cursor: pointer;" onclick="window.location='orders.php';">
      <img src='https://cdn-icons-png.flaticon.com/128/839/839860.png'>
      <h2>Заказы</h2>
    </div>
    <div class='cards_my' style="cursor: pointer;" onclick="window.location='favourite.php';">
      <img src='https://cdn-icons-png.flaticon.com/128/130/130195.png'>
      <h2>Избранное</h2>
    </div>
  </section>
</div>
<?php include "includes/footer.php" ?>
</body>
</HTML>
