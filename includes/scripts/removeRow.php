<?php
session_start();
$conn = mysqli_connect("localhost", "root", "1111", "shop");
if(!$conn) {
   header('Location: http://127.0.0.1/FashionBoutique/includes/error.php');
   exit;
}
header('Content-Type: application/json');
mysqli_set_charset($conn, "utf8");
mysqli_autocommit($conn, FALSE);
mysqli_begin_transaction($conn); //запускаем транзакцию
$data = json_decode(file_get_contents('php://input'), true);
$table_name = $data['table'];
$columns = $data['columns'];
$nomer = $data['nomer'];
$data = $data['data'];
if ($_SESSION['user'] === 'admin') {
	foreach ($data as $key => $value) {
		$sql = 'DELETE FROM '.$table_name.' WHERE ';
		for ($i=0; $i<count($columns); ++$i) {
			$sql .= $columns[$i].'='.$value[$nomer[$i]].' AND '; //список nomer содержит номера(порядок) ключевых полей
		}
		$sql = substr($sql, 0, -4);
		$res = mysqli_query($conn, $sql);
		if ($res === false) {
			echo '{"status": "'.mysqli_error($conn).'"}';
			mysqli_rollback($conn);
			exit();
		}
	}
	mysqli_commit($conn);
	echo '{"status": "success"}';
}
else {
	echo '{"status": "auth"}';
}
//echo '{"status": "didnt get anything"}';
?>