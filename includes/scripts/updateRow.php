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
$column_num = (int)$data['nomer'] - 1;
$newData = $data['newData'];
$oldData = $data['oldData'];
if ($_SESSION['user'] === 'admin') {
	if ($types[$column_num][1]==='s')
		$value = $types[$column_num][0].'="'.$newData.'"';
	else
		$value = $types[$column_num][0].'='.$newData;
	$uslovia='';
	for ($i=0; $i<count($types); $i++) {
		if ($types[$i][2]==='PRI')
			$uslovia .= $types[$i][0].'='.$oldData[$i].' AND ';
	}
	$uslovia = substr($uslovia, 0, -4);
	$sql = 'UPDATE '.$table_name.' SET '.$value.' WHERE '.$uslovia.';';
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