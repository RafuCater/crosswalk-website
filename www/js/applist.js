
var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                  "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
var goodImage = false;

// Load icon image when image selected and show in template
function onImgSelect(input) {

    // Check file size!/Full file API support.
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
        alert ("The image file is too large. Limit 1MB. 300x300px recommended dimensions.");
        goodImage = false;
        return;
    }
    //load preview window
    var reader = new FileReader();
    reader.onload = function (e) {
        $('#pvwImg').attr('src', e.target.result);
    };
    reader.readAsDataURL(input.files[0]);
    goodImage = true;
}

function onGooglePlayUrlChange(value) {
    $('#pvwImg').attr ("title", "On click URL: " + value);
}

function onAppNameChange(value) {
    $('#pvwLabel').text (value);
}
function onAuthorChange(value) {
    $('#pvwAuthor').text (value);
}
function onAuthorUrlChange(value) {
    $('#pvwAuthor').attr ("title", "On click URL: " + value);
}
function onPublishDateChange(value) {
    var dateText = value;
    var d = new Date (value);
    if (!isNaN( d.getTime())) {
        dateText = monthNames[d.getMonth()] + " " + d.getFullYear();
    }
    $('#pvwPublishDate').text ("Published: " + dateText);
}

function getNumEst (formVal) {
    val = formVal.replace(/\,/g,'');
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

function onDownloadsChange(value) {
    $('#pvwDownloads').text ("Downloads: " + getNumEst(value));
}
function onPriceChange(value) {}  

//update all preview fields (only called when page loaded)
function updatePreview() {
    onGooglePlayUrlChange ($("input[name=googleUrl]").val());
    onAppNameChange ($("input[name=name]").val());
    onAuthorChange ($("input[name=author]").val());
    onAuthorUrlChange ($("input[name=authorUrl]").val());
    onPublishDateChange ($("input[name=publishDate]").val());
    onDownloadsChange ($("input[name=downloads]").val());

    //Deselect picture.  I don't know how to load it
    onDownloadsChange ($("input[name=imageFile]").val(""));
}


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


// If an error occurs, the user can return to their form
// without losing all their entries via this function
function showFormDiv() {
    $('html,body').animate({scrollTop:0},0);
    $(".appSubmitDiv").css("display","block");
    $(".appResultDiv").css("display","none");
}



//--------- App Insert into DB from form ---------------


function isEmpty(val) {
    return $.trim(val) == '';
}

$(document).ready(function()
{
    //refresh preview if there are values in form already
    updatePreview();

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
        if ($('input[name=price]').val() > 200) {
            alert ("You wish! Maximum price allowed is $200.");
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
                    $(".appSubmitResult").html(data);
                } else {
                    alert ("fake success");
                    $(".appSubmitResult").html(data + 'data.error: <pre><code>'+data.error+'</code></pre>');
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

