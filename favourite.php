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
.card-product .gallery {
  height: 100%;
  width: 50%;
}
.card-product .next, .card-product .previous {
  display: none;
}
.card-product a {
  text-decoration: none;
}
.card-product input {
  width: 20px;
  height: 20px;
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
#buttons {
  display: flex;
  gap: 20px;
  align-items: center;
  justify-content: center;
}
</style>
<link rel="stylesheet" type="text/css" href="includes/css/my.css">
<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<h1>Избранное</h1>
	<section style='display: flex; margin: 20px; justify-content: space-between;'>
    <section style='display:flex; align-items: center;'>
		<img src='pictures/user.svg' height='100px' width='100px'>
    <section style='display: flex; flex-direction: column;'>
      <h3><?= $name; ?></h3>
      <h3 style='color: grey;'><?= $phone; ?></h3>
    </section></section>
    <form method='post' action='includes/scripts/exit.php' style='position: relative; display: flex; gap: 10px;'>
    <a href='settings.php' id='settings' class='user-button'><img src='pictures/settings.svg' class='user-button'></a>
    <button type='submit' id='settings'><img src='pictures/exit.svg' class='user-button'></button>
    </form></section>
  <section style='display: flex; flex-wrap: wrap; justify-content: space-around; gap: 15px;'>
    <?php
      if( $conn ) {
        $sql = "SELECT * FROM view_favourite WHERE buyer=".$_SESSION['user'].";";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result)==0) {
          echo 'Пока что здесь пусто';
        }
        else {
        while ($row = mysqli_fetch_array($result)) { 
          $dir = getcwd(); // получаем текущий каталог
          $path = "db\product\\".$row['id_group'];
          $dir .= '\\'.$path;
          $papka = $row['product'];
          $dir .= '\\'.$papka;
          $path .= '\\'.$papka;
            if ($dh = opendir($dir)) // открываем каталог
            {
                echo '<div class="card-product" id="'.$row['id_group'].' '.$papka.'">';
                echo '<div class="gallery js-flickity" id="gallery js-flickity">';
                while (($file = readdir($dh)) !== false) 
                {
                  if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
                  echo '<div class="gallery-cell"><img src="'.$path.'\\'.$file.'"></div>';
                }
                closedir($dh); // закрываем каталог
                echo '</div>';
            }
            echo '<section style="width: 30%;"><a href="product.php?group='.$row['id_group'].'&product='.$papka.'"><h2>'.$row['name'].'</h2>';
            echo '<h2>'.$row['price'].'</h2><h2>Цвет: '.$papka.'</h2></a>';
            echo '<button onclick="removeFromFavourite('.$row['id_group'].', '.$papka.')">Убрать из избранного</button>';
            echo '</section></div>';
        }
      }}
    ?>
  </section>
<?php include "includes/footer.php" ?>
<script src='includes/js/removeFromUserProduct.js'></script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</body>
</HTML>