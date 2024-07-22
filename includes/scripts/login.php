<?php
session_start();
header('Content-Type: application/json');
$conn = mysqli_connect("localhost", "root", "1111", "shop");
mysqli_set_charset($conn, "utf8");
$data = json_decode(file_get_contents('php://input'), true);
if( $conn ) {
  $phone = $data['phone'];
  $password = $data['password'];
  if ($phone==='89080586154' and $password==='1111') {
    $_SESSION['user'] = 'admin';
    echo '{"status": "admin"}';
  }
  else {
    $sql = "SELECT phone, id, password FROM buyer WHERE phone='".$phone."' AND password='".$password."' LIMIT 1;";

    $result = mysqli_query($conn, $sql);
    if ($result !== false) {
      while ($row = mysqli_fetch_array($result)) { 
        $_SESSION['user'] = $row['id'];
        echo '{"status": "success"}';
      }
    }
    else {
      echo '{"status": "incorrect"}';
    }	
  }
}
?>