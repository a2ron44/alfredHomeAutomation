<?php
$result = new stdClass();
$result->returnCode = 0;
$result->msg = 'Unknown';
$result->data = null;

$sendVal = $_POST['sendVal'];

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

$cmd = '183*' . $sendVal;

exec("/usr/bin/python /alfred/www/alfredPHP/controller.py 0" . $cmd, $output);

$result->returnCode = 1;
$result->msg = $output;

echo json_encode($result);
return;

