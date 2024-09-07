<HTML>
<?php include "includes/header.php" ?>
<?php
if( $conn ) {
 $sql = "SELECT phone, id, FIO, password, email FROM buyer WHERE id=".$_SESSION['user'].";";

  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_array($result)) { 
    $FIO = explode(' ', $row['FIO']);
    $phone = $row['phone'];
    $passwd=$row['password'];
    $email=$row['email'];
  }
}
?>
<style>
.card-product {
  display: flex;
  flex-direction: row;
  width: 40%;
  background-color: white;
  border-radius: 10px;
  padding: 15px;
  gap: 15px;
  justify-content: flex-start;
}
.card-product input {
  width: 20px;
  height: 20px;
}
.card-product .gallery {
  height: 100%;
  width: 50%;
}
.card-product .next, .card-product .previous {
  display: none;
}
.card-product button {
  background-color: #a3cdf1;
  border-radius: 5px;
  padding: 5px;
  font-size: 20px;
}
.card-product img {
  width: 300px;
  height: 300px;
  object-fit: cover;
}
.card-product a {
	text-decoration: none;
}
#buttons {
  display: flex;
  gap: 20px;
  align-items: center;
  justify-content: center;
}
</style>
<link rel="stylesheet" type="text/css" href="includes/css/my.css">
<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<h1>Настройки</h1>
	<section style='display: flex; margin: 20px; justify-content: space-between;'>
    <section style='display:flex; align-items: center;'>
		<img src='pictures/user.svg' height='100px' width='100px'>
    <section style='display: flex; flex-direction: column;'>
      <h3><?= $FIO[0].' '.$FIO[1]; ?></h3>
      <h3 style='color: grey;'><?= $phone; ?></h3>
    </section></section>
    <form method='post' action='includes/scripts/exit.php' style='position: relative; display: flex; gap: 10px;'>
    <a href='settings.php' id='settings' class='user-button'><img src='pictures/settings.svg' class='user-button'></a>
    <button type='submit' id='settings'><img src='pictures/exit.svg' class='user-button'></button>
    </form></section>
  <section style='display: flex; flex-wrap: wrap; justify-content: space-around; gap: 15px;'>
   <form method='post' action='' style='display: flex; flex-direction: column; width: 85%; gap: 10px; color: #22223b;' id='registration'>
      <label for='name'>Ваше имя:</label>
      <input name='name' value=<?php echo $FIO[0] ?> required>
      <label for='surname'>Ваша фамилия:</label>
      <input name='surname' value=<?php echo $FIO[1] ?> required>
      <label for='phone'>Ваш номер телефона:</label>
      <input name='phone' type='tel' value=<?php echo $phone ?> required>
      <label for='email'>Ваша почта:</label>
      <input name='email' type='email' value=<?php echo $email ?> required>
      <label for='password'>Ваш пароль:</label>
      <input name='password' type='password' value='' required>
      <button style='background-color: #a3cdf1; border-radius: 5px; padding: 5px; font-size: 20px;' type='submit'>Принять изменения</button>
    </form>
  </section>
<?php include "includes/footer.php" ?>
<?php
if( $conn ) {
  if (!empty($_POST)) {
    $name = $conn->real_escape_string($_POST['name'].' '.$_POST['surname']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    
    $id=$_SESSION['user'];
    $sql = "UPDATE buyer SET FIO='$name', phone='$phone', email='$email', password='$password' WHERE id='$id'";

    $result = mysqli_query($conn, $sql);

    if ($result == false) {
      echo '<script type="text/javascript">';
      echo 'alert("Произошла ошибка, пожалуйста, убедитесь в правильности своих данных");';
      echo '</script>';
    }
    else {
      echo '<script type="text/javascript">';
      echo 'alert("Изменения успешно внесены!");';
      echo '</script>';
    }
  }
}
?>
<script src='includes/js/removeFromUserProduct.js'></script>
<script src='includes/js/createOrder.js'></script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</body>
</HTML>