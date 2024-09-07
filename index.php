<!DOCTYPE html>
<html lang='ru'>
    <?php include "includes/header.php"; ?>
      <link rel="stylesheet" href="includes/css/main.css">
      <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">

      <section class='img-back' style="background-image: url('pictures/f4.jpg'); height: 100%; width: 100%;">
      <section style='display: flex; flex-direction: column; align-items: flex-end;'>
        <h1 style='padding-right: 25px; padding-top: 25px;'>Fashion Boutique</h1>
        <p style='margin: 0px; padding-right: 25px; text-align: right; width: 40%; word-break: break-all; position: relative;'>Компания Fashion Boutique постоянно работает над созданием новых проектов, которые помогают развивать моду и поддерживать актуальность бренда. Например, компания организует модные показы, участвует в международных выставках и проводит мастер-классы для дизайнеров.</p></section>
      <section style='display: flex; flex-direction: column; align-items: flex-start;'>
        <h1 style='padding-left: 25px; padding-top: 25px;'>Fashion Boutique</h1>
        <p style='margin: 0px; padding-left: 25px; text-align: left; width: 30%; position: relative;'>Компания Fashion Boutique предлагает своим клиентам широкий ассортимент одежды от ведущих мировых дизайнеров и местных талантов. Каждый сезон компания обновляет свою коллекцию, чтобы удовлетворить потребности самых взыскательных покупателей.<br><br><br></p></section>
    </section>
    <div class="skewed">
      <h2 style='color: white; margin-top: 0px; letter-spacing: 3px;'>Найдите что-то для себя</h2>
      <section class="search-bar">
      <input type="search" id='search' placeholder="Найти...">
      <button onclick="search();"><img src='https://cdn-icons-png.flaticon.com/128/2319/2319177.png' height='90%'></button></section>
    </div>
    <h2 style='margin: 0;'>Новости</h2>
    <div class="gallery js-flickity" id='gallery js-flickity' height='350px'>
        <?php
                $sql = 'SELECT id, name FROM news LIMIT 10;';

                $result = mysqli_query($conn, $sql);
                
                while ($row = mysqli_fetch_array($result)) {
                    $dir = getcwd(); // получаем текущий каталог
                    $path = "db\\news\\".$row['id'];
                    $dir = $dir.'\\'.$path;
                    if ($dh = opendir($dir)) { // открываем каталог
                        // считываем по одному файл или подкаталогу
                        // пока не дойдем до конца
                        echo '<div class="gallery-cell"><a href="news.php#'.$row['id'].'"><div class="card-news">';
                        while (($file = readdir($dh)) !== false) {
			    if($file=="." || $file == "..") continue;  //пропустить ссылки на другие папки
                            echo '<img src="'.$path.'\\'.$file.'">';
			    break;
                        }
                        closedir($dh); // закрываем каталог
                    }
                    echo '<h3>'.$row['name'].'</h3>';
                    echo '</div></a></div>';
		}
        ?>
	<div class="gallery-cell"><a href="news.php"><div class="card-news"><h3>Читать дальше</h3></div></a></div>
    </div>

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
		$sql = 'SELECT * FROM view_product group by id_group;';

		$result = mysqli_query($conn, $sql);
		
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
    ?>  
    </div>
  </section>
<?php include "includes/footer.html"; ?>
<script>
function search() {
    text = document.getElementById('search').value;
    if (text=='')
        alert('Введите текст в строку поиска');
    else
        window.open('search.php?text=' + text, '_self')
}
</script>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>
<script src="includes/js/addToBasket.js"></script>
<script src="includes/js/removeFromUserProduct.js"></script>
</html>
