<HTML>
<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<link rel="stylesheet" type="text/css" href="includes/css/product.css">
<?php include "includes/header.php"; ?>
  <section>
  <div class='gallery js-flickity' id='gallery js-flickity' data-flickity-options='{"wrapAround": true }'>
  <?php
	$id_group=$_GET['group'];
	$id = $_GET['product'];
  $favourite = false; //находится ли в избранном
  $basket = false; //находится ли в корзине
  if (isset($_SESSION['user'])) {
    $sql = 'SELECT favourite, basket from user_product where buyer='.$_SESSION['user'].' and id_group='.$id_group.' and product='.$id.';';
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
      if ($row['favourite']===1)
        $favourite=true;
      if ($row['basket']===1)
        $basket=true;
    }
  }
	$sql = 'SELECT * FROM view_product WHERE product='.$id.' and id_group='.$id_group.';';

	$result = mysqli_query($conn, $sql);
		
		while ($row = mysqli_fetch_array($result)) {
      $dir = getcwd(); // получаем текущий каталог
      $path = "db\product\\".$id_group;
      $dir .= '\\'.$path;
	    $scan = scandir($dir); #смотрим всю папку, все цвета

	    $dir .= '\\'.$id; #добавляем в путь выбранный цвет
	    $path .= '\\'.$id; #добавляем в условный путь выбранный цвет
      if ($dh = opendir($dir)) // открываем каталог
            {
                // считываем по одному файл или подкаталогу
                // пока не дойдем до конца
                while (($file = readdir($dh)) !== false) 
                {
                    // пропускаем символы .. и .
                    if($file=='.' || $file=='..') continue;
                    echo '<div class="gallery-cell"><img src="'.$path.'\\'.$file.'"></div>';
                }
                closedir($dh); // закрываем каталог
            }
      $description=$row['description'];
      $price=$row['price'];
	    echo "</div>";
      if ($favourite) 
    	 echo "<h1>".$row['name'].'<span id="'.$row['id_group'].' '.$id.'span" onclick="addToFavourite('.$row['id_group'].', '.$id.'); return false;" class="favourite"><img src="pictures/heart.svg"></span></h1>';
      else
        echo "<h1>".$row['name'].'<span id="'.$row['id_group'].' '.$id.'span" onclick="removeFromFavourite('.$row['id_group'].', '.$id.'); return false;" class="favourite"><img src="pictures/heart2.svg"></span></h1>';
	    echo '<section class="info"><section><p>Цвет: '.$row['color']."</p>";
    }
	 for ($i=2; $i<count($scan); $i++) { #добавляем ссылки на другие цвета
      $sql = 'SELECT color from view_product where id_group='.$id_group.' and product='.$scan[$i].';';
      $result = mysqli_query($conn, $sql);
      $row=mysqli_fetch_array($result);
		  echo '<a href="product.php?group='.$id_group.'&product='.$scan[$i].'">'.$row['color'].'</a>';
	 }
   echo "</section><h1 style='align-self: center;'>".$price."</h1></section>";
   echo "<p>".$description."</p>";
   ?>
   <section style='display: flex; justify-content: space-between;'>
   <div style='cursor: pointer; display: flex; gap: 10px;'> <img src='https://cdn-icons-png.flaticon.com/128/1828/1828884.png' id='star'>
   <?php
    $sql = 'SELECT get_rating('.$id.', '.$id_group.') as rate;';  #вызываем ХП, которая рассчитывает рейтинг
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
      if ($row['rate'] == 0 or $row['rate'] == NULL)
        echo '<p href="reviews.php?group='.$id_group.'&product='.$id.'">У этого товара еще нет отзывов</p></div>';
      else
        echo '<p href="reviews.php?group='.$id_group.'&product='.$id.'">'.$row['rate'].'</p></div>';
    }
    if (!$basket) 
      echo '<button class="basket" id="'.$id_group.' '.$id.'button" onclick="removeFromBasket('.$id_group.', '.$id.'); return false;"><img src="https://cdn-icons-png.flaticon.com/128/5392/5392794.png">  Убрать из корзины</button>'; 
    else
      echo '<button class="basket" id="'.$id_group.' '.$id.'button" id="'.$id.'button" onclick="addToBasket('.$id_group.', '.$id.'); return false;"><img src="https://cdn-icons-png.flaticon.com/128/5392/5392794.png"> Добавить в корзину</button>'; 
   ?>
    </section>
  </section>
</div>
<?php include "includes/footer.php"; ?>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<script src="includes/js/addToBasket.js"></script>
<script src="includes/js/removeFromUserProduct.js"></script>
</HTML>
