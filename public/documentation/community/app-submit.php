<?php

// functions in this file work like this (except printResultDiv): 
//    return bool for success/fail, 
//    will set global $retVal before exit (contains error msg or function-specific value)

$retVal = "";
$valueArray = array(); 

// Create return result web page
function printResultDiv ($error) {
    $ret = 
      "<table width=100%>" .
        "<tr>" . 
	  "<td style='text-align:center;width: 80px; padding: 10px'><img src='" .
            (empty($error) ? "/assets/apps/submit-app-success-icon.png" : "/assets/apps/submit-app-fail-icon.png") . "'>" .
          "</td>" .
	  "<td style='padding: 10px; vertical-align:middle;'>" .
            (empty($error) ? 
      //success
           "<strong>Application Submitted Successfully</strong><br>" . 
           "Your application will be reviewed and, once accepted, will be added to the " . 
           "<a href='/documentation/community/apps.php'>Crosswalk Applications</a> page." : 
      //error
           "<strong>Submission Error</strong><br>" . 
           "The application submission failed.<br><br>" . $error . 
           "<br><br>We are sorry for the error. If the error appears to be due to incorrect form data, please check " .
           "your values and <a href='/documentation/community/app-submit.html'>resubmit the application</a>.") . 
         "</td>" .
	"</tr>" . 
      "</table>";
    echo $ret;
}

function addRecordToDb () {
    global $retVal;
    global $valueArray;

    if (!empty($valueArray['spam'])) {
        //spam.  prettend to succeed with no warning
        return true;
    }
    
    $db  = new mysqli('localhost', 'xwalkweb', 'webapps', 'xwalk');
    if ($db->connect_errno) {
        $retVal = "Unable to connect to database. " . $db->connect_error;
        return false;
    }

    // Insert values into DB
    $cmd = "INSERT INTO xwalk_apps (google_play_url, name, author, author_url, email, publish_date, num_downloads, price, size, xdk, category, version, notes) 
            VALUES (    $valueArray['googleUrl'],
                        $valueArray['name'],
                        $valueArray['author'],
                        $valueArray['authorUrl'],
                        $valueArray['email'],
                        $valueArray['publishDate'],
                        $valueArray['downloads'],
                        $valueArray['price'], 
                        $valueArray['size'],
                        //TBD: arch
                        $valueArray['xdk'],
                        $valueArray['category'],
                        $valueArray['version'],
                        $valueArray['notes']";

    $result = $db->query($cmd);
    if (!$result) {
        $retVal = "Error: The application \"{$name}\" could not be added to the xwalk application database. (" . $db->error . ")";
        $db->close();
        return false;
    } 
    $db->close();
    return true;
}

function uploadImageFile() {
    global $retVal;
    global $valueArray;

//    $uploadDir = "/srv/www/stg.crosswalk-project.org/_db-app-images/";
    $uploadDir = "/home/bob/src/crosswalk/website/_db-app-images/";

    $errArray = array(UPLOAD_ERR_INI_SIZE  => 'The image file size exceeds the 2MB allowed limit.',
                      UPLOAD_ERR_FORM_SIZE => 'The image file size exceeds the 2MB allowed limit.',
                      UPLOAD_ERR_PARTIAL   => 'The image file was only partially uploaded.',
                      UPLOAD_ERR_NO_FILE   => 'No image file was uploaded.',
                      UPLOAD_ERR_NO_TMP_DIR =>'No temporary image folder on the server was accessible.',
                      UPLOAD_ERR_CANT_WRITE =>'The image file could not be saved on the server.');

    try {
        // Check if undefined, multiple files, or $_FILES corruption attack
        echo "checking!";
        if (!isset($_FILES["imageFile"]['error']) ||
             is_array($_FILES['imageFile']['error'])) {
            throw new RuntimeException('Invalid form parameters.');
        }
        $fileError = $_FILES['imageFile']['error'];
        if ($fileError != UPLOAD_ERR_OK) {
            if (isset ($errArray[$fileError])) {
                throw new RuntimeException($errMsg[$fileError]);
            } else {
                throw new RuntimeException('An unknown error occurred while uploading the image.');
            }
        }

        // Check filesize (should have already been caught, but just in case)
        if ($_FILES['imageFile']['size'] > 2000000) {
            throw new RuntimeException('Image file exceeds size limit.');
        }

        // We assume that a valid tmp_name file has been created
        $tmpName = $_FILES["imageFile"]['tmp_name'];

        // Check MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search (
            $finfo->file ($tmpName),
            array ('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'),
            true)) {
            throw new RuntimeException('Invalid file format. Image file must be jpg, png, or gif.');
        }
        echo '<br><br>GOT TO HERE<br><br>';

        // Get unique name for file (we will store in DB)
        $uniqueName = sprintf ("%s/%s/%s", $uploadDir, sha1_file($tmpName), $ext);

        echo "<br><br>unuqueName: $uniqueName<br><br>";

        if (!move_uploaded_file ($tmpName, $uniqueName)) {
            throw new RuntimeException('Failed to move uploaded image file.');
        }
        $valueArray['fileName'] = $uniqueName;
    } catch (RuntimeException $e) {
        $retVal = $e->getMessage();
        return false;
    }
    return true;
}


function getFormValues() {
    global $retVal;
    global $valueArray;

    //insecure way: $name = $_POST['name'];
    $valueArray['spam']  =  mysqli_real_escape_string($db, $_POST['title']);
    $valueArray['googleUrl'] = mysqli_real_escape_string($db, $_POST['googleUrl']);
    $valueArray['name']  =   mysqli_real_escape_string($db, $_POST['name']);
    $valueArray['author']  = mysqli_real_escape_string($db, $_POST['author']);
    $valueArray['authorUrl']  = mysqli_real_escape_string($db, $_POST['authorUrl']);
    $valueArray['email']  = mysqli_real_escape_string($db, $_POST['email']);
    $valueArray['publishDate']  = mysqli_real_escape_string($db, $_POST['publishDate']);
    $valueArray['downloads']  = mysqli_real_escape_string($db, $_POST['downloads']);
    $valueArray['price']  = mysqli_real_escape_string($db, $_POST['price']);
    $valueArray['size']  = mysqli_real_escape_string($db, $_POST['size']);
   //TBD: arch
    $valueArray['xdk']  = mysqli_real_escape_string($db, $_POST['xdk']);
    $valueArray['category']  = mysqli_real_escape_string($db, $_POST['category']);
    $valueArray['version']  = mysqli_real_escape_string($db, $_POST['version']);
    $valueArray['notes']  = mysqli_real_escape_string($db, $_POST['notes']);

    // Validate input
    if (strlen($valueArray['name'])==0 || strlen($valueArray['author'])==0 || strlen($valueArray['email'])==0) {
        $retVal = "One of the required form fields is empty. Application submission denied.";        
        return false;
    }
    return true;
}

// A quick check that there are values
function quickValidate() {
    global $retVal;

    if (!isset($_POST["name"]) ||
        !isset($_FILES["imageFile"]['error']) ||
        !isset($_FILES["imageFile"]['tmp_nam'])) {
        $retVal = "One or more form parameters are invalid. Application submission rejected.";
        return false;
    }
    return true;
}



// ------ Start --------------
print_r ($_POST);
print_r ($_FILES);

if (!quickValidate()) {
    printResultDiv ($retVal);
    exit;
}
if (!getFormValues()) {
    printResultDiv ($retVal);
    exit;
}
if (!uploadImageFile()) {
    printResultDiv ($retVal);
    exit;
}
if (!addRecordToDb ()) {
    printResultDiv ($retVal);
    exit;
}
// Success, print with no parameters
printResultDiv ();


/*
+----------------+---------------+------+-----+---------+-------+
| Field          | Type          | Null | Key | Default | Extra |
+----------------+---------------+------+-----+---------+-------+
| appid          | int(11)       | NO   | PRI | 0       |       |
| name           | varchar(255)  | NO   |     | NULL    |       |
| author         | varchar(100)  | NO   |     | NULL    |       |
| publish_date   | date          | YES  |     | NULL    |       |
| num_downloads  | int(11)       | YES  |     | NULL    |       |
| image          | varchar(255)  | YES  |     | NULL    |       |
| price          | decimal(6,2)  | YES  |     | NULL    |       |
| size           | int(11)       | YES  |     | NULL    |       |
| architecture   | bit(6)        | YES  |     | NULL    |       |
| xdk            | bit(1)        | YES  |     | NULL    |       |
| category       | varchar(100)  | YES  |     | NULL    |       |
| version        | varchar(100)  | YES  |     | NULL    |       |
| description    | varchar(500)  | YES  |     | NULL    |       |
| author_url     | varchar(2000) | YES  |     | NULL    |       |
| goole_play_url | varchar(2000) | YES  |     | NULL    |       |
+----------------+---------------+------+-----+---------+-------+
*/

