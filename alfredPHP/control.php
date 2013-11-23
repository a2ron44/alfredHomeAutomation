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

$deviceNum = substr($devId, -2);
$cmd = $deviceNum . $sendVal;

//exec("sudo /home/pi/dloads/rcswitch-pi/send " . $deviceNum . " " . $sendVal, $output, $ret);
//exec("sudo /home/pi/dloads/rcswitch-pi/receive " . $deviceNum . " " . $sendVal, $output, $ret);
//exec("sudo /usr/bin/python /opt/test.py", $output, $ret);

exec("/usr/bin/python /alfred/www/alfredPHP/controller.py " . $cmd, $output);

$result->returnCode = 1;
$result->msg = $output;

echo json_encode($result);
return;





