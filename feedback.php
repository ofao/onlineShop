<HTML>
<?php include "includes/header.php" ?>
<?php
if( $conn ) {
  $product = $_GET['product'];
  $id = $_GET['order'];
  $group=$_GET['group'];
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
  width: 100%;
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
</style>
<link rel="stylesheet" type="text/css" href="includes/css/my.css">
<link rel="stylesheet" type="text/css" href="includes/css/rating.css">
<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<h1>Оставьте отзыв</h1>
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
    <section>
    <?php
      if( $conn ) {
        $sql = "SELECT * FROM view_product WHERE id_group=".$group." and product=".$product.";";
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) { 
          $dir = getcwd(); // получаем текущий каталог
          $path = "db\product\\".$row['id_group'];
          $dir .= '\\'.$path;
          $papka = $row['product'];
          $dir .= '\\'.$papka;
          $path .= '\\'.$papka;
            if ($dh = opendir($dir)) // открываем каталог
            {
                echo '<div class="card-product" id="'.$row['id_group'].' '.$row['product'].'">';
                echo '<div class="gallery js-flickity" id="gallery js-flickity">';
                while (($file = readdir($dh)) !== false) 
                {
                  if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
                  echo '<div class="gallery-cell"><img src="'.$path.'\\'.$file.'"></div>';
                }
                closedir($dh); // закрываем каталог
                echo '</div>';
            }
            echo '<section><a href="product.php?group='.$row['id_group'].'&product='.$row['product'].'"><h2>'.$row['name'].'</h2>';
            echo '<p>Цвет: '.$row['color'].'</p></a>';
            echo '<div class="rating-area"><input type="radio" id="star-5" name="rating" value="5"><label for="star-5" title="Оценка «5»"></label><input type="radio" id="star-4" name="rating" value="4"><label for="star-4" title="Оценка «4»"></label>    <input type="radio" id="star-3" name="rating" value="3"><label for="star-3" title="Оценка «3»"></label><input type="radio" id="star-2" name="rating" value="2"><label for="star-2" title="Оценка «2»"></label><input type="radio" id="star-1" name="rating" value="1"><label for="star-1" title="Оценка «1»"></label></div>';
            echo '</section></div>';
        }
      }
      echo '<iframe id="myframe" width="100%" height="70%" src="textEditor/index.php"></iframe>';
      echo '<button onclick="createFeedback('.$id.');">Оставить отзыв</button>';
    ?>
  </section>
<?php include "includes/footer.php" ?>
<script src="includes/js/feedback.js"></script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</body>
</HTML>