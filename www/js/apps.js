
/*jslint browser: true*/
/*global $, jQuery, alert*/

var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "June",
                  "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
var goodImage = false;

// Load icon image when image selected and show in template
function onImgSelect(input) {
    "use strict";
    // Check file size!/Full file API support.
    var fileApiSupported = (window.FileReader && window.File && window.FileList && window.Blob);
    if (!fileApiSupported) {
        $('#pvwImg').attr('src', "/assets/apps/no-file-api.jpg");
        goodImage = true;  //just guess since we can't get more details
        return;
    }
    
    //make sure it is an image format
    if (input.files.length !== 1 ||
        !(input.files[0].type.match('image.*'))) {
        alert("The file selected was not an image");
        goodImage = false;
        return;
    }
    //check size (limit 1MB, should be ~4K)
    if (input.files[0].size > 1000000) {
        alert("The image file is too large. Limit 1MB. 300x300px recommended dimensions.");
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

function isEmpty(val) {
    return $.trim(val) === '';
}

function onStoreUrlChange(value) {
    if (isEmpty(value)) {
        value = "<App Store URL>";
    }
    $('#pvwImg').attr("title", "On click URL: " + value);
}

function onAppNameChange(value) {
    if (isEmpty(value)) {
        value = "Application Name";
    }
    $('#pvwLabel').text (value);
}
function onAuthorChange(value) {
    if (isEmpty(value)) {
        value = "Author";
    }
    $('#pvwAuthor').text (value);
}
function onAuthorUrlChange(value) {
    if (isEmpty(value)) {
        value = "<Author URL>";
    }
    $('#pvwAuthor').attr ("title", "On click URL: " + value);
}

function getDateEst (inVal) {
    var d = new Date (inVal);
    if (isNaN( d.getTime())) {
        outVal = "N/A";
    } else {
        outVal = monthNames[d.getMonth()] + " " + d.getFullYear();
    }
    return outVal;
}

function onPublishDateChange(value) {
    $('#pvwPublishDate').text ("Published: " + getDateEst(value));
}

function getNumEst (inVal) {
    if (isEmpty(inVal)) {
        return "N/A";
    }
    var outVal = inVal.toString().replace(/\,/g,'');
    if (isNaN(outVal) || outVal==="") {
        outVal = "N/A";
    } else if (outVal >= 1000 && outVal < 1000000) {
        outVal = ((Math.floor (outVal/1000))) + "K+";
    } else if (outVal >= 1000000 && outVal < 1000000000) {
        outVal = ((Math.floor (outVal/1000000))) + "M+";
    } else if (outVal >= 1000000000) {
        outVal = "Whoa!";
    }
    return outVal;                           
}

function onDownloadsChange(value) {
    $('#pvwDownloads').text ("Downloads: " + getNumEst(value));
}
function onPriceChange(value) {}  

//update all preview fields (only called when page loaded)
function updatePreview() {
    onStoreUrlChange ($("input[name=storeUrl]").val());
    onAppNameChange ($("input[name=name]").val());
    onAuthorChange ($("input[name=author]").val());
    onAuthorUrlChange ($("input[name=authorUrl]").val());
    onPublishDateChange ($("input[name=publishDate]").val());
    onDownloadsChange ($("input[name=downloads]").val());

    //Deselect picture.  I don't know how to load it
    $("input[name=imageFile]").val("");
}

// If an error occurs, the user can return to their form
// without losing all their entries via this function
function showFormDiv() {
    $('html,body').animate({scrollTop:0},0);
    $(".appSubmitDiv").css("display","block");
    $(".appResultDiv").css("display","none");
}

//--------- App Insert into DB from form ---------------

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
            goodImage === false) {

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

        if (window.FormData === undefined) {  // for HTML5 browsers
            alert ("This browser does not support the form upload feature. Please use a newer browser.");
            return;
        }

        var formData = new FormData(this);

        $.ajax({
            url: formURL,
            type: 'POST',
            data:  formData,
            mimeType:"multipart/form-data",
            cache: false,
            contentType: false,
            processData:false,
            success: function(data, textStatus, jqXHR) {
                if (typeof data.error == 'undefined') {
                    $(".appSubmitResult").html(data);
                } else {
                    $(".appSubmitResult").html('An error occured during form submission. ' + data.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $(".appSubmitResult").html('The AJAX Request Failed. ' + errorThrown);
            }          
        });

        $(".appSubmitDiv").css("display","none");
        $(".appResultDiv").css("display","block");
    }); 
});

//==================================================

/*
--- Finished app div: ----
<div class='appCube' id='cube0'>
  <div class='appLabel'>Tiny Flashlight LED</div>

  <a href='https://play.google.com/store/apps/details?id=com.devuni.flashlight&hl=en'>
    <img class='appImg' id='appImg0' src='/assets/apps/tiny-flashlight-led84.jpg'/>
  </a>

  <div class='appDetailLabel'>
    <a href='https://www.intel.com'>
      <div class='appAuthor'>Nikolay Ananiev</div>
    </a>
    <div>Published: Jan 2015</div>
    <div>Downloads: 50M+</div>
  </div>
</div>
*/

// Create application div for apps.php 
function printResultDiv (row) {
    var hasStoreUrl = (row['storeUrl'] && (row['storeUrl'].length > 0));
    var hasAuthorUrl = (row['authorUrl'] && (row['authorUrl'].length > 0));
    if (!hasAuthorUrl && hasStoreUrl) {
        row['authorUrl'] = row['storeUrl']; //use store URl if no author URL
        hasAuthorUrl = true;
    }
    var output = 
        "<div class='appCube'>\n" +
        "  <div class='appLabel'>" + row['name'] + "</div>\n" +
        (hasStoreUrl ? 
            "  <a href='" + row['storeUrl'] + "'>\n" + 
            "    <img class='appImg' src='/assets/apps/icons/" + row['image'] + "'/>\n" +
            "  </a>\n"
        :   "  <img class='appImgNoLink' src='/assets/apps/icons/" + row['image'] + "'/>\n") +

        "  <div class='appDetailLabel'>\n" + 
        (hasAuthorUrl ? 
            "    <a href='" + row['authorUrl'] + "'>\n" + 
            "      <div class='appAuthor'>" + row['author'] + "</div>\n" +
            "    </a>\n"
        :   "     <div class='appAuthorNoLink'>" + row['author'] + "</div>\n") +
        "    <div>Published: " + (row['publishDate'] ? getDateEst (row['publishDate'])
                                  : "N/A") + "</div>\n" +
        "    <div>Downloads: " + (row['downloads']   ? getNumEst (row['downloads'])
                                  : "N/A") + "</div>\n" + 
        "  </div>\n" +
        "</div>\n";
    return output;
}

// Go through all apps and create divs
function displayApps(dbRows, where) {
    var output = "";
    for (i=0; i<dbRows.length; i++) {
        var row = dbRows[i];
        output += printResultDiv (row);
    };
    output += "<br clear='all' /><br>(App count: " + dbRows.length + ")";
    return output;
}

