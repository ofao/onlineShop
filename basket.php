<HTML>
<?php include "includes/header.php" ?>
<?php
if( $conn ) {
 $sql = "SELECT phone, id, FIO, region FROM buyer WHERE id=".$_SESSION['user'].";";

  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_array($result)) { 
    $name = $row['FIO'];
    $phone = $row['phone'];
    $region = $row['region'];
  }
}
?>
<style>
.card-product {
  background-color: white;
  width: 40%;
  border-radius: 10px;
  padding: 15px;
}
.card-product-section {
  display: flex;
  flex-direction: row;
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
.card-product .favourite img {
  height: 20px;
  width: 20px;
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
<h1>Корзина</h1>
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
        $sql = "SELECT * FROM view_basket WHERE buyer=".$_SESSION['user'].";";

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
                echo '<div class="card-product" id="'.$row['id_group'].' '.$row['product'].'"><section class="card-product-section">';
                echo '<input type="checkbox">';
                echo '<div class="gallery js-flickity" id="gallery js-flickity">';
                while (($file = readdir($dh)) !== false) 
                {
                  if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
                  echo '<div class="gallery-cell"><img src="'.$path.'\\'.$file.'"></div>';
                }
                closedir($dh); // закрываем каталог
                echo '</div>';
            }
            echo '<section style="width: 30%;"><a href="product.php?group='.$row['id_group'].'&product='.$row['product'].'"><h2>'.$row['name'].'</h2>';
            echo '<h2 style="color: #357ae8;">'.$row['price'].'</h2><h3>Цвет: '.$row['color'].'</a></h3>';
            echo '<section id="buttons"><button onclick="add('.$row['product'].', '.$row['id_group'].');">+</button><p id="p'.$row['id_group'].' '.$row['product'].'">1</p><button onclick="minus('.$row['product'].', '.$row['id_group'].');">-</button></section>';
            echo '</section></section><button onclick="removeFromUserProduct('.$row['id_group'].', '.$row['product'].')">Убрать из корзины</button>';
            $sql2 = "SELECT quantity FROM kolvo_product inner join view_stocks on view_stocks.id=kolvo_product.id_stock WHERE region='".$region."' and id_group=".$row['id_group']." and id_product=".$row['product']." limit 1;";

            $result2 = mysqli_query($conn, $sql2);
            if (mysqli_num_rows($result2)==0) {
              echo '<h4 id="nal'.$row['id_group'].' '.$row['product'].'">Нет в наличии</h4>';
              echo '<script>document.getElementById("'.$row['id_group'].' '.$row['product'].'").firstChild.firstChild.setAttribute("disabled", true);</script>';
            }
            else {
              while ($row2 = mysqli_fetch_array($result2)) {
                echo '<h4 id="nal'.$row['id_group'].' '.$row['product'].'">В наличии: '.$row2['quantity'].'</h4>';
              }
            }
            echo '</div>';
        }
        echo '</section>';
        echo 'Ваш регион: '.$region.'<br>';
        echo '<label for="addres">Выберите адрес:</label><br>';
        echo '<select name="addres" id="addres">';
        $sql = 'SELECT addres from view_pvzs where region="'.$region.'";';
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) { 
          echo '<option value="'.$row['addres'].'">'.$row['addres'].'</option>';
        }
        echo '</select><br>';
      }}
 ?>
<button style='background-color: #a3cdf1; border-radius: 5px; padding: 5px; font-size: 20px;' onclick='createOrder();'>Оформить заказ<button>
  <script type='text/javascript'>
  function add(id, group) {
    p = document.getElementById('p' + group.toString() + ' ' + id.toString());
    maxi = document.getElementById('nal' + group.toString() + ' ' + id.toString()).innerHTML;
    if (maxi!='Нет в наличии') {
      if (Number(maxi.split(': ')[1]) != Number(p.innerHTML))
        p.innerHTML = (Number(p.innerHTML) + 1).toString();
    }
  }
  function minus(id, group) {
    p = document.getElementById('p' + group.toString() + ' ' + id.toString());
    let num = Number(p.innerHTML);
    if (num == 1)
      return;
    p.innerHTML = (num - 1).toString();
  }
</script>
<?php include "includes/footer.php" ?>
<script src='includes/js/removeFromUserProduct.js'></script>
<script src='includes/js/createOrder.js'></script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
</body>
</HTML>