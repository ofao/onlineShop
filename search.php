<!DOCTYPE html>
<html lang='ru'>
    <?php include "includes/header.php";
    $text = $_GET['text'];
    if (isset($_GET['category'])) {
        $category = $_GET['category'];
    }
    else
	   $category = 'Все категории';
    echo '<script> category="'.$category.'";</script>';
    ?>
      <link rel="stylesheet" href="includes/css/main.css">
      <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
      <section>
          <input id='search-input' type="search" placeholder="Найти..." value="<?php echo $text;?>">
    	  <label for='category'>Категория товаров:</label>
    	  <select name='category' id='category'>
            <option value='Все категории'>Все категории</option>  
            <option value='Женская одежда'>Женская одежда</option>  
            <option value='Мужская одежда'>Мужская одежда</option> 
            <option value='Детская одежда'>Детская одежда</option> 
            <option value='Обувь'>Обувь</option>  
            <option value='Аксессуары'>Аксессуары</option> 
          </select>
          <button onclick='search();'>Поиск</button>
      </section>
  <section style='width: 100%'>
    <h2>Товары</h2>
    <div class="cards">
    <?php
		$ids = [];
		if (isset($_SESSION['user'])) {
			$sql = 'SELECT id_group from user_product where buyer='.$_SESSION['user'].';';
			$result = mysqli_query($conn, $sql);
			while ($row = mysqli_fetch_array($result)) {
				$ids[] = $row['id_group'];
			}
		}
        if ($category === 'Все категории')
            $sql = 'SELECT * FROM view_product where name like "%'.$text.'%";';
        else 
		  $sql = 'SELECT * FROM view_product where name like "%'.$text.'%" and category like "%'.$category.'%";';

		$result = mysqli_query($conn, $sql);
		
        if (mysqli_num_rows($result)==0) {
          echo 'Не смогли найти товары по вашему запросу';
        }
        else {
    		while ($row = mysqli_fetch_array($result)) {
    			if (in_array($row['id_group'], $ids)) continue; #пропускаем товары, которые есть в корзине
                $dir = getcwd(); // получаем текущий каталог
                $path = "db\product\\".$row['id_group'];
                $dir .= '\\'.$path;
    	        $papka = $row['product']; //первый цвет товара
    	        $dir .= '\\'.$papka;
    	        $path .= '\\'.$papka;
                if ($dh = opendir($dir)) // открываем каталог
                {
                    echo '<div class="card">';
                    echo '<div class="gallery js-flickity" id="gallery js-flickity">';
                    while (($file = readdir($dh)) !== false) 
                    {
            		    if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
                        echo '<div class="gallery-cell"><img class="card-img" src="'.$path.'\\'.$file.'"></div>';
                    }
                    closedir($dh); // закрываем каталог
                }
        		echo '</div>';
                echo '<a style="text-decoration: none;" href="product.php?group='.$row['id_group'].'&product='.$papka.'">';
                echo '<h4>'.$row['name'].'</h4></a>';
    		echo '<h1 class="price">'.$row['price'].'<span id="'.$row['id_group'].' '.$papka.'span" onclick="addToFavourite('.$row['id_group'].', '.$papka.'); return false;" class="favourite"><img src="pictures/heart.svg"></span></h1>';
    		echo '<p>'.$row['description'].'</p><button id="'.$row['id_group'].' '.$papka.'button" class="addToBasket" onclick="addToBasket('.$row['id_group'].', '.$papka.'); return false;"><img src="https://cdn-icons-png.flaticon.com/128/5392/5392794.png" style="width: 25px; height: 25px; vertical-align: middle;">   Добавить в корзину</button></div>';
    		}
        }
    ?>  
    </div>
  </section>
<?php include "includes/footer.html"; ?>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<script src="includes/js/addToBasket.js"></script>
<script>
    document.querySelectorAll('option[value="' + category + '"]')[0].setAttribute('selected', true);
function search() {
    item=document.getElementById('search-input').value;
    categ = document.getElementById('category').value;
    if (categ=='Все категории')
        window.open('search.php?text=' + item, '_self');
    else
        window.open('search.php?text=' + item + '&category=' + categ, '_self');
}
</script>
</html>