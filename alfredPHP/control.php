<?php
$result = new stdClass();
$result->returnCode = 0;
$result->msg = 'Unknown';
$result->data = null;

$devId = $_POST['devId'];
$sendVal = $_POST['sendVal'];

if(empty($devId)){
	$result->returnCode = -1;
	$result->msg = 'No DeviceID';
	
	echo json_encode($result);
	return;
}

switch($sendVal){
	case 0:
	case 1:
		break;
	default:
		$result->returnCode = -1;
		$result->msg = 'Value not supported';
		
		echo json_encode($result);
		return;
}

$deviceNum = substr($devId, -1);

exec("/var/www/send " . $deviceNum . " " . $sendVal);

$result->returnCode = 1;

echo json_encode($result);
return;





