<?php
session_start();
header('Content-Type: application/json');
$conn = mysqli_connect("localhost", "root", "1111", "shop");
mysqli_set_charset($conn, "utf8");
$data = json_decode(file_get_contents('php://input'), true);
if( $conn ) {
  $name = $data['name'].' '.$data['surname'];
  $phone = $data['phone'];
  $email = $data['email'];
  $password = $data['password'];
  
  $sql = "INSERT INTO buyer(FIO, phone, email, password, region) VALUES('$name', '$phone', '$email', '$password', 'Челябинская область')";

  $result = mysqli_query($conn, $sql);

  if ($result == false) {
      echo '{"status": "incorrect"}';
  }
  else {
    $_SESSION['user'] = mysqli_insert_id($conn); #возвращает id зарегистрированного пользователя
    echo '{"status": "success"}';
  }
}
?>