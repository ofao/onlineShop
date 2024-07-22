<?php
session_start();
$conn = mysqli_connect("localhost", "root", "1111", "shop");
if( $conn )
   mysqli_set_charset($conn, "utf8");
else {
   header('Location: includes/error.php');
   exit;
}
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$id = $data['productId'];
$addres = $data['pvzaddres'];
if (isset($_SESSION['user'])) {
	$sql = 'SELECT region from buyer where id='.$_SESSION['user'].';';
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_array($result)) { 
		$region = $row['region'];
	}
	$sql = 'SELECT id from view_pvzs where addres="'.$addres.'" AND region="'.$region.'";';
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_array($result)) { 
		$pvz_id = $row['id'];
	}
	foreach ($id as &$arr) {
		$sql = "INSERT INTO purchase VALUES(0, ".$_SESSION['user'].", ".$arr[0].", ".$arr[1].", ".$arr[2].", 2, '2020-12-12', 0, ".$pvz_id.", 1, '');";
		$result = mysqli_query($conn, $sql);
		$sql2 = 'select favourite, basket from user_product where buyer='.$_SESSION['user'].' AND product='.$arr[1].' AND id_group='.$arr[0].';';
		$result = mysqli_query($conn, $sql2);
		while ($row = mysqli_fetch_array($result)) {
			$fav = $row['favourite'];
			$bask = $row['basket'];
		}
		if ($fav==0 and $bask==0) {
			$sql = 'DELETE FROM user_product WHERE buyer='.$_SESSION["user"].' AND product='.$product_id.' AND id_group='.$group_id.';';
		}
		else {
			$sql = "UPDATE user_product SET basket=0 WHERE buyer=".$_SESSION['user']." and id_group=".$arr[0]." and product=".$arr[1].";";
		}
		$result = mysqli_query($conn, $sql);
	}
	if($result)
		echo '{"status": "success"}';
	else
		echo '{"status": "'.mysqli_error($conn).'"}';
}
else {
	echo '{"status": "auth"}';
	#echo json_encode(array('success' => 'need to be authorized'));
	#return false;
}
?>