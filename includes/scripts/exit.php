<?php
session_start();
unset($_SESSION['user']);
header('Location: http://127.0.0.1/FashionBoutique/index.php');
?>