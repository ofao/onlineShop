<!DOCTYPE html>
<style type="text/css">
  .news {
    display: flex;
    flex-direction: column;
    width: 100%;
    background-color: ghostwhite;
    border-radius: 5px;
  }
  .news h2, .news p, .news a {
    margin: 10px;
  }
  p {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
</style>
<html lang='ru'>
      <link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
      <?php include "includes/header.php" ?>
      <section style='display: flex; flex-direction: column; gap: 15px; width: 100%;'>
        <?php 
          $conn = mysqli_connect("localhost", "root", "1111", "shop");
          if( $conn ) {
            mysqli_set_charset($conn, "utf8");
            $sql = "SELECT id, name, description, data FROM news;";

            $result = mysqli_query($conn, $sql);
            
            while ($row = mysqli_fetch_array($result)) {
              echo "<section class='news'><a name='".$row['id']."'>";

              $dir = getcwd(); // получаем текущий каталог
              $path = "db\\news\\".$row['id'];
              $dir = $dir.'\\'.$path;
              if ($dh = opendir($dir)) // открываем каталог
              {
                  // считываем по одному файл или подкаталогу
                  // пока не дойдем до конца
                  echo "<div class='gallery js-flickity' id='gallery js-flickity' data-flickity-options='{\"wrapAround\": true }'>";
                  while (($file = readdir($dh)) !== false) {
                      // пропускаем символы .. и .
                      if($file=='.' || $file=='..') continue;
                      echo '<div class="gallery-cell"><img src="'.$path.'\\'.$file.'"></div>';
                  }
                  closedir($dh); // закрываем каталог
                  echo "</div>";
              }
              echo "<h2>".$row['name']."<span style='float: right; font-size: 20px; color: grey;'>".$row['data']."</span></h2></a>";
              echo "<p>".$row['description']."</p><a href='' onclick='show_more(this); return false;'>Читать дальше</a>";
              echo "</section>";
            }
          }
        ?>
      </section>
<?php include "includes/footer.html" ?>
</div>
</body>
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.js"></script>
<script type="text/javascript">
function show_more(show_a) {
  var p = show_a.previousSibling;
  if (show_a.innerHTML === "Скрыть") {
    show_a.innerHTML = "Читать дальше";
    p.setAttribute('style', 'white-space: nowrap; overflow: hidden;');
  } else {
    show_a.innerHTML = "Скрыть";
    p.setAttribute('style', 'white-space: break-spaces; overflow: visible;');
  }
}
</script>
</html>
