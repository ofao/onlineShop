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
$types = $data['types'];
$data = $data['data'];
if ($_SESSION['user'] === 'admin') {
	$types_str = '';
	$headers = '';
	$values='';
	for ($i=0; $i<count($types); $i++) {
		$types_str .= $types[$i][1];
		$headers .= $types[$i][0].',';
		if ($types[$i][1]==='s')
			$values .= '"'.$data[$i].'",';
		else
			$values .= $data[$i].',';
	}
	$headers = substr($headers, 0, -1);
	$values = substr($values, 0, -1);
	$sql = 'INSERT INTO '.$table_name.' ('.$headers.') VALUES ('.$values.');';
	$res = mysqli_query($conn, $sql);
	if ($res === false) {
		echo '{"status": "'.mysqli_error($conn).'"}';
		mysqli_rollback($conn);
		exit();
	}
	mysqli_commit($conn);
	echo '{"status": "success"}';
}
else {
	echo '{"status": "auth"}';
}
//echo '{"status": "didnt get anything"}';
?>