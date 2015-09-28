
$(document).ready(function() {
  $("#datepicker").datepicker();
});

var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                  "July", "Aug", "Sep", "Oct", "Nov", "Dec"];

// Load icon image when image selected and show in template
function onImgSelect(input) {
  if (input.files['length'] != 1 || 
  !input.files[0].type.match('image.*')) {
    alert ("The file selected was not an image");
    return;
  }
  var reader = new FileReader();
  reader.onload = function (e) {
      $('#pvwImg')
      .attr('src', e.target.result);
  };
  reader.readAsDataURL(input.files[0]);
}
  
function onGooglePlayUrlChange(input) {
  $('#pvwImg').attr ("title", "On click URL: " + input.value);
}
function onAppNameChange(input) {
  $('#pvwLabel').text (input.value);
}

function onAuthorChange(input) {
  $('#pvwAuthor').text (input.value);
}
function onAuthorUrlChange(input) {
  $('#pvwAuthor').attr ("title", "On click URL: " + input.value);
}
function onPublishDateChange(input) {
  var dateText = input.value;
  var d = new Date (input.value);
  if (!isNaN( d.getTime())) {
    dateText = monthNames[d.getMonth()] + " " + d.getFullYear();
  }
  $('#pvwPublishDate').text ("Published: " + dateText);
}

function getNumEst (val) {
  if (isNaN(val) || val=="") {
    val = "N/A";
  } else if (val >= 1000 && val < 1000000) {
    val = ((Math.floor (val/1000))) + "K+";
  } else if (val >= 1000000 && val < 1000000000) {
    val = ((Math.floor (val/1000000))) + "M+";
  } else if (val >= 1000000000) {
    val = "Whoa!";
  }
  return val;                           
}
function onDownloadsChange(input) {
  $('#pvwDownloads').text ("Downloads: " + getNumEst(input.value));
}
function onPriceChange(input) {}	  

//--------- App Insert into DB from form ---------------

$(".appSubmitForm").submit (function() { 
    return false; //prevent the page from refreshing
});

$(".appSubmitBtn").click (function() {
    $.post( 
	    $(".appSubmitForm").attr("action"),
	    $(".appSubmitForm :input").serializeArray(),
	    function(result){
            $("#appSubmitResult").html(result);
	    }
     );
});
