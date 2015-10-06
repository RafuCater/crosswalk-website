<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

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
           "your values and <a onclick='showFormDiv();return false;' href=''>resubmit the application</a>.") .
         "</td>" .
	"</tr>" . 
      "</table>";
    echo $ret;
}

function addRecordToDb () {
    global $retVal;
    global $valueArray;

    $retVal = "";
    if (!empty($valueArray['spam'])) {
        //spam.  prettend to succeed with no warning
        return true;
    }

    try {
        $mysqli  = new mysqli('localhost', 'xwalkweb', 'webapps', 'xwalk');
        if ($mysqli->connect_errno) {
            throw new RuntimeException("Unable to connect to database. " . $mysqli->connect_error);
        }

        // Insert values into DB
        if (!($stmt = $mysqli->prepare ("INSERT INTO xwalk_apps (storeUrl, name, image, author, authorUrl, email, publishDate, downloads, price, size, architecture, xdk, category, version, notes, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)"))) {
            throw new RuntimeException("Unable to prepare database insert statement. " . $mysqli->error);
        }
            
        //16 values
        if (!($stmt->bind_param ('sssssssidisissss', 
                           $valueArray['storeUrl'],
                           $valueArray['name'],
                           $valueArray['image'],
                           $valueArray['author'],
                           $valueArray['authorUrl'],
                           $valueArray['email'],
                           $valueArray['publishDate'],
                           $valueArray['downloads'],
                           $valueArray['price'], 
                           $valueArray['size'],
                           $valueArray['arch'],
                           $valueArray['xdk'],
                           $valueArray['category'],
                           $valueArray['version'],
                           $valueArray['notes'],
                           $valueArray['status']))) {
            throw new RuntimeException("Unable to bind parameters for databaes INSERT statement. " . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new RuntimeException("Unable to execute database INSERT statement. " . $stmt->error);
        }
        $stmt->close();
    } catch (RuntimeException $e) {
        $mysqli->close();
        $retVal = $e->getMessage();
        return false;
    }
    $mysqli->close();
    return true;
}


function uploadImageFile() {
    global $retVal;
    global $valueArray;

    $retVal = "";
    //$uploadDir = "/srv/www/stg.crosswalk-project.org/_db-app-images/";
    $uploadDir = "/home/bob/src/crosswalk/website/_db-app-images/";

    $errArray = array(UPLOAD_ERR_INI_SIZE  => 'The image file size exceeds the 2MB allowed limit.',
                      UPLOAD_ERR_FORM_SIZE => 'The image file size exceeds the 2MB allowed limit.',
                      UPLOAD_ERR_PARTIAL   => 'The image file was only partially uploaded.',
                      UPLOAD_ERR_NO_FILE   => 'No image file was uploaded.',
                      UPLOAD_ERR_NO_TMP_DIR =>'No temporary image folder on the server was accessible.',
                      UPLOAD_ERR_CANT_WRITE =>'The image file could not be saved on the server.');

    try {
        // Check if undefined, multiple files, or $_FILES corruption attack
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
/*
      Currently failing on staging server:  
     Fatal error: Class 'finfo' not found in /srv/www/stg.crosswalk-project.org/www/documentation/community/app-submit.php on line 133
*/
        $uniqueName = $_FILES["imageFile"]['name'];
        if (class_exists ( "finfo" )) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            if (false === $ext = array_search (
                $finfo->file ($tmpName),
                array ('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'),
                true)) {
                throw new RuntimeException('Invalid file format. Image file must be jpg, png, or gif.');
            }

            // Get unique name for file (we will store in DB)
            $uniqueName = sprintf ("%s.%s", sha1_file($tmpName), $ext);
        }

        if (!move_uploaded_file ($tmpName, sprintf ("%s/%s", $uploadDir, $uniqueName))) {
            throw new RuntimeException('Failed to move uploaded image file.');
        }
        $valueArray['image'] = $uniqueName;
    } catch (RuntimeException $e) {
        $retVal = $e->getMessage();
        return false;
    }
    return true;
}

function sanitizeInput($data) {
    $data = trim ($data);
    $data = stripslashes ($data);
    $data = htmlspecialchars ($data);
    return $data;
}

function getFormValues() {
    global $retVal;
    global $valueArray;
    $retVal = "";

    $valueArray['spam']  =     sanitizeInput ($_POST['title']);
    $valueArray['storeUrl'] =  sanitizeInput ($_POST['storeUrl']);
    $valueArray['name']  =     sanitizeInput ($_POST['name']);
    $valueArray['author']  =   sanitizeInput ($_POST['author']);
    $valueArray['authorUrl'] = sanitizeInput ($_POST['authorUrl']);
    $valueArray['email']  =    sanitizeInput ($_POST['email']);

    $publishDate = strtotime(sanitizeInput ($_POST['publishDate']));
    $valueArray['publishDate'] = ($publishDate ? date('Y-m-d', $publishDate) : null);

    $valueArray['downloads'] = str_replace(',', '', sanitizeInput ($_POST['downloads']));

    $valueArray['price'] =     sanitizeInput ($_POST['price']);
    $valueArray['size'] =      sanitizeInput ($_POST['size']);

    $arch = "";
    if (isset($_POST['archArm']) && $_POST['archArm']=='1') {
        $arch .= 'arm,';
    }
    if (isset($_POST['archx86']) && $_POST['archx86']=='1') {
        $arch .= 'x86,';
    }
    if (isset($_POST['archx86_64']) && $_POST['archx86_64']=='1') {
        $arch .= 'x86_64,';
    }
    $valueArray['architecture'] = rtrim($arch, ",");

    if (isset($_POST['xdk']) && $_POST['xdk']=='1') {
        $valueArray['xdk'] = 1;
    }

    $valueArray['category'] =  sanitizeInput ($_POST['category']);
    $valueArray['version'] =   sanitizeInput ($_POST['version']);
    $valueArray['notes'] =     sanitizeInput ($_POST['notes']);
    //add status value (one of "pending", "accepted", "rejected")
    $valueArray['status'] =    "pending";

    // Validate input
    if (strlen($valueArray['name'])==0 || strlen($valueArray['author'])==0 || 
        strlen($valueArray['email'])==0) {
        $retVal = "One of the required form fields is empty. Application submission denied.";        
        return false;
    }
    return true;
}

// A quick check that there are values
function quickValidate() {
    global $retVal;
    $retVal = "";

    if (!isset($_POST['name']) ||
        !isset($_FILES['imageFile']['error']) ||
        !isset($_FILES['imageFile']['tmp_name'])) {
        $retVal = "One or more form parameters are invalid. Application submission rejected.";
        return false;
    }
    return true;
}


// ------ Start --------------
// needed?  if (!$_SERVER["REQUEST_METHOD"] == "POST") {

//echo "<pre><code>";
//print_r ($_POST);
//print_r ($_FILES);
//echo "</code></pre>";

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
// Success
printResultDiv (null);

//echo "<pre><code>";
//print_r ($valueArray);
//echo "</code></pre>";

/*
+--------------+---------------+------+-----+---------+----------------+
| Field        | Type          | Null | Key | Default | Extra          |
+--------------+---------------+------+-----+---------+----------------+
| appid        | int(11)       | NO   | PRI | NULL    | auto_increment |
| status       | varchar(20)   | NO   |     | NULL    |                |
| storeUrl     | varchar(2000) | YES  |     | NULL    |                |
| name         | varchar(255)  | NO   |     | NULL    |                |
| image        | varchar(255)  | NO   |     | NULL    |                |
| author       | varchar(100)  | NO   |     | NULL    |                |
| authorUrl    | varchar(2000) | YES  |     | NULL    |                |
| email        | varchar(100)  | NO   |     | NULL    |                |
| publishDate  | date          | YES  |     | NULL    |                |
| downloads    | int(11)       | YES  |     | NULL    |                |
| price        | decimal(6,2)  | YES  |     | NULL    |                |
| size         | int(11)       | YES  |     | NULL    |                |
| architecture | varchar(100)  | YES  |     | NULL    |                |
| xdk          | bit(1)        | YES  |     | NULL    |                |
| category     | varchar(100)  | YES  |     | NULL    |                |
| version      | varchar(100)  | YES  |     | NULL    |                |
| notes        | varchar(500)  | YES  |     | NULL    |                |
+--------------+---------------+------+-----+---------+----------------+
*/

