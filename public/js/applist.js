
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                  "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
var goodImage = false;

// Load icon image when image selected and show in template
function onImgSelect(input) {

    // Check file size!/Full File API support.
    var fileApiSupported = (window.FileReader && window.File && window.FileList && window.Blob);
    if (!fileApiSupported) {
        $('#pvwImg').attr('src', "/assets/apps/no-file-api.jpg");
        goodImage = true;  //just guess since we can't get more details
        return;
    }
    
    //make sure it is an image format
    if (input.files['length'] != 1 || 
        !input.files[0].type.match('image.*')) {
        alert ("The file selected was not an image");
        goodImage = false;
        return;
    }
    //check size (limit 1MB, should be ~4K)
    if (input.files[0].size > 1000000) {
        alert ("The image file is too large. Limit 1MB. 192x192px recommended dimensions.");
        goodImage = false;
        return;
    }
    //load preview window
    var reader = new FileReader();
    reader.onload = function (e) {
        $('#pvwImg')
            .attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
    goodImage = true;
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


/*
function createPreviewHtml() {
    var output = 
"    <div class='appDisplayPvw'> \
      <div class='appCube' id='pvwCube'> \
        <div class='appLabel' id='pvwLabel'>" + $('[name=name]').val() + "</div> \
        <img class='appImg' id='pvwImg'  \
             src='/assets/apps/missing-image.jpg' \
             title="<Google Play URL>"/>
        <div class='appDetailLabel'>
          <div class='appAuthor' id='pvwAuthor' 
               title="On click URL: <Author URL>">Author</div>
          <div id='pvwPublishDate'>Published: &lt;TBD&gt;</div>
          <div id='pvwDownloads'>Downloads: &lt;TBD&gt;</div>
        </div>
      </div>
      <div class='appPreviewLabel'>Display Preview</div>
    </div>
}
*/

//--------- App Insert into DB from form ---------------


function isEmpty(val) {
    return $.trim(val) == '';
}

$(document).ready(function()
{
    $("#datepicker").datepicker();


    //Callback handler for form submit event
    $(".appSubmitForm").submit(function(e) {
        e.stopPropagation(); // Stop stuff happening
        e.preventDefault(); //Prevent Default action. 

        //double-check values
        $('html,body').animate({scrollTop:0},0);
        if (isEmpty($('input[name=name]').val()) || 
            isEmpty($('input[name=imageFile]').val()) ||
            isEmpty($('input[name=author').val()) ||
            isEmpty($('input[name=email]').val()) || 
            goodImage == false) {

            alert ("Error: One or more required fields were empty or not the correct format.");
            if (!goodImage) {
                $('input[name=imageFile]').focus();
            }
            return;
        }

        // spinner: $("#multi-msg").html("<img src='loading.gif'/>");
        var formObj = $(this);
        var formURL = formObj.attr("action");

        if (window.FormData == undefined) {  // for HTML5 browsers
            alert ("old browser.  No upload");
            return;
        }

        var formData = new FormData(this);

        alert ("about to ajax this baby");
        $.ajax({
            url: formURL,
            type: 'POST',
            data:  formData,
            mimeType:"multipart/form-data",
            cache: false,
            contentType: false,
            processData:false,
            success: function(data, textStatus, jqXHR) {
                if (typeof data.error === 'undefined') {
                    alert ("real success");
                    $(".appSubmitResult").html('<pre><code>' + data + '</code></pre>');
                } else {
                    alert ("fake success");
                    $(".appSubmitResult").html('<pre><code>'+data+'...error: '+data.error+'</code></pre>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert (textStatus);
                $(".appSubmitResult").html('<pre><code class="prettyprint">AJAX Request Failed<br/> textStatus='+textStatus+', errorThrown='+errorThrown+'</code></pre>');
            }          
        });

        $(".appSubmitDiv").css("display","none");
        $(".appResultDiv").css("display","block");
        alert ("finished");
    }); 
});



/*

$(".appSubmitForm").submit (function() { 
    return false; //prevent page from refreshing
});


$(".appSubmitBtn").click (function() {
    $('html,body').animate({scrollTop:0},0);
    if (isEmpty($('input[name=name]').val()) || 
        isEmpty($('input[name=imageFile]').val()) ||
        isEmpty($('input[name=author').val()) ||
        isEmpty($('input[name=email]').val()) || 
        goodImage == false) {

        alert ("Error: One or more required fields were empty or not the correct format.");
        if (!goodImage) {
            $('input[name=imageFile]').focus();
        }
        return;
    }

    alert ("about to post...");
    $.post( 
	$(".appSubmitForm").attr("action"),
	$(".appSubmitForm :input").serializeArray(),
	function (result) {
            alert (result);
            $(".appSubmitResult").html(result);
            $(".appSubmitDiv").css("display","none");
            $(".appResultDiv").css("display","block");
            alert ("done");
p        }
     );
});

*/
