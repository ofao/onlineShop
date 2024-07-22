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
$id = $data['orderId'];
$rating = $data['rating'];
$comment = $data['comment'];
if($id !== null) {
	if (isset($_SESSION['user'])) {
		$sql = 'INSERT INTO feedback values('.$id.', '.$rating.', "2020-12-12", "'.$comment.'");';
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