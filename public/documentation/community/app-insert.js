
$('#app-submit-form').submit (function() { 
    return false; //prevent the page from refreshing
});

$('#insert').click (function() {
    $.post( 
	$('#app-submit-form').attr('action'),
	$('#app-submit-form :input').serializeArray(),
	function(result){
	    $('#result').html(result);
	}
    );
});
