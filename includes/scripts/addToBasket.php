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
if($product_id !== null and $group_id !== null) {
	if (isset($_SESSION['user'])) {
		$sql = 'INSERT INTO user_product values('.$_SESSION["user"].', '.$group_id.', '.$product_id.', 0, 1) ON DUPLICATE KEY UPDATE basket=1;';
		if(mysqli_query($conn, $sql))
			echo '{"status": "success"}';
		else
			echo '{"status": "'.mysqli_error($conn).'"}';
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