<?php
?>

<!DOCTYPE html>
<html>
<head>
<title>Alfred Home Automation</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="./js/jquery.mobile-1.3.2.min.css"
	type="text/css">
<script type="text/javascript" src="./js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="./js/jquery.mobile-1.3.2.min.js"></script>
</head>

<style>
#deviceTable {
	width: 90%;
}

#deviceTable td {
	border-bottom: 1px solid #bbb;
}
</style>

<body>
	<div data-role="page">

		<div data-role="header">
			<h1>Alfred Home Automation</h1>
		</div>
		<!-- /header -->

		<div data-role="content">

			<table id="deviceTable">
				<tr>
					<td><div data-role="fieldcontain" class="ui-field-contain ui-body ">
							<label for="device_10">Lamp:</label> <select name="device_10"
								id="device_10" data-role="slider" class="dev_switchOF">
								<option value="5526844">Off</option>
								<option value="5526835" selected>On</option>
							</select>

						</div></td>
				</tr>
				<tr>
					<td>
						<div data-role="fieldcontain" class="ui-field-contain ui-body ">
							<label for="device_11">Night Light:</label> <select
								name="device_11" id="device_11" data-role="slider" class="dev_switchOF">
								<option value="5526988">Off</option>
								<option value="5526979">On</option>
							</select>
						</div>
					</td>
				</tr>
			</table>

		</div>
		<!-- /content -->

	</div>
	<!-- /page -->

</html>
<script type="text/javascript">

$(document).ready(function(){

$('.dev_switchOF').on('change', function(event){
	var devId = $(this).attr('id');
	var val = $(this).val();
	var sendVal = 0;
	var obj = new Object();

	var url =  './control.php';

	obj.sendVal = val;

	$.ajax({
         type: "POST",
         url: url,
         dataType: 'json',
         data:  obj,
         success: function(result){
             if(result.returnCode < 1){
                 alert(result.msg);
             }
         }
     })
	
});

});
</script>
