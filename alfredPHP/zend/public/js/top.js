// global js functions   10/7/11 - Aaron
$(document).ready(function() {
    $(".datepick").datepicker({
    dateFormat: 'mm/dd/yy' ,
    showButtonPanel: true

    });
    $(".datetimepick").datetimepicker({
    hourGrid: 4,
    minuteGrid: 10,
    stepMinute: 10
    });

    
  //fucking IE...
	if ( "onhelp" in window ) {
        // (jQuery cannot bind "onhelp" event)
        window.onhelp = function () {
            return false;
        };
    }
	
});  // document ready
