<?php
session_start();
$conn = mysqli_connect("localhost", "root", "1111", "shop");
if( $conn )
   mysqli_set_charset($conn, "utf8");
else {
   header('Location: includes/error.php');
   exit;
}
if (!isset($_SESSION['user']))
	$auth='<a href="authentification.php">'; 
else
	$auth='<a href="my.php">';
?>
<!DOCTYPE html>
<html lang="ru">
  <link rel="stylesheet" type="text/css" href="includes/css/style_ofelia.css">
  <link rel="stylesheet" type="text/css" href="includes/css/divider-wave.css">
  <head>
    <title>Fashion Boutique</title>
    <link rel="icon" type="image/x-icon" href="pictures/2.svg">
  </head>
  <body>
    <nav>
    <h1>Fashion Boutique
      <img src="pictures/2.svg" alt="logo" width="50px" height="50px" align="center"></h1>
    <a class='menu' id='menu'><img src='pictures/menu.png' height='50px' width='50px'></a>
    <ol id="menubar" class='menubar'>
      <li><a href="index.php">На главную</a></li>
      <li><a href="news.php">Новости</a></li>
      <li><a href="catalog.php">Каталог</a>
        <ul id='dropdown' class='dropdown'>
          <li><a href='search.php?text=Женская+одежда'>Женская одежда</a></li>
          <li><a href='search.php?text=Мужская+одежда'>Мужская одежда</a></li>
          <li><a href='search.php?text=Детская+одежда'>Детская одежда</a></li>
          <li><a href='search.php?text=Обувь'>Обувь</a></li>
          <li><a href='search.php?text=Аксессуары'>Аксессуары</a></li>
        </ul>
      </li>
      <li><?php echo $auth; ?>
	Мой кабинет</a></li>
      <li><a href="about.php">О нас</a>
        <ul id='dropdown' class='dropdown'>
          <li><a href='history.php'>История компании</a></li>
        </ul></li>
    </ol>
  </nav>
  <aside id="side-menubar" class='side-menubar'>
    <ol>
      <li><a href="index.php">На главную</a></li>
      <li><a href="news.php">Новости</a></li>
      <li><a href="catalog.php">Каталог</a>
        <ul id='dropdown' class='dropdown'>
          <li><a href='search.php?text=Женская+одежда'>Женская одежда</a></li>
          <li><a href='search.php?text=Мужская+одежда'>Мужская одежда</a></li>
          <li><a href='search.php?text=Детская+одежда'>Детская одежда</a></li>
          <li><a href='search.php?text=Обувь'>Обувь</a></li>
          <li><a href='search.php?text=Аксессуары'>Аксессуары</a></li>
        </ul>
      </li>
      <li><?php echo $auth; ?>
	Мой кабинет</a></li>
      <li><a href="about.php">О нас</a>
        <ul id='dropdown' class='dropdown'>
          <li><a href='history.php'>История компании</a></li>
        </ul>
      </li>
    </ol></aside>
  <div id='content'>
    <div id='mask'></div>
    <main class='main-content'>
</html>