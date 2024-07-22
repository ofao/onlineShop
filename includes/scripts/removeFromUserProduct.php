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
$product_id = $data['productId'];
$group_id = $data['groupId'];
$field = $data['field'];
if($product_id !== null) {
	if (isset($_SESSION['user'])) {
		$sql = 'select favourite, basket from user_product where buyer='.$_SESSION['user'].' AND product='.$product_id.' AND id_group='.$group_id.';';
		$result = mysqli_query($conn, $sql);
		while ($row = mysqli_fetch_array($result)) {
			$fav = $row['favourite'];
			$bask = $row['basket'];
		}
		if ($fav==0 and $bask==0) {
			$sql = 'DELETE FROM user_product WHERE buyer='.$_SESSION["user"].' AND product='.$product_id.' AND id_group='.$group_id.';';
		}
		else {
			$sql = 'UPDATE user_product SET '.$field.'=0 WHERE buyer='.$_SESSION["user"].' AND product='.$product_id.' AND id_group='.$group_id.';';
		}
		if(mysqli_query($conn, $sql))
			echo '{"status": "success"}';
		else
			echo '{"status": "fail to insert"}';
	}
	else {
		echo '{"status": "auth"}';
		#echo json_encode(array('success' => 'need to be authorized'));
		#return false;
	}
}
else
	echo '{"status": "didnt get anything"}';
?>