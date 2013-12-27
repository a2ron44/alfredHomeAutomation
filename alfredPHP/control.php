<?php
$result = new stdClass();
$result->returnCode = 0;
$result->msg = 'Unknown';
$result->data = null;

$sendVal = $_POST['sendVal'];


$cmd = '183*' . $sendVal;

exec("/usr/bin/python /alfred/www/alfredPHP/controller.py O " . $cmd, $output);

$result->returnCode = 1;
$result->msg = $output;

echo json_encode($result);
return;

