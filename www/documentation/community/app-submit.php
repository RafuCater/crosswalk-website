<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);


if (!isset($_FILES["imageFile"]['error']) ||
    is_array($_FILES['imageFile']['error'])) {
    echo "No imageFile info in array.  Exiting...";
    exit;
}

$errMsg = array(1 => 'php.ini max file size exceeded', 
                2 => 'html form max file size exceeded', 
                3 => 'file upload was only partial', 
                4 => 'no file was attached'); 
$error = $_FILES['imageFile']['error'];
if ($error > 0) {
    echo "Error:  '" . $errMsg[$error] . "' <br>";
}


print_r ($_POST);
print_r ($_FILES);


// functions in this file work like this:  
//    return bool for success/fail, 
//    will set global $retVal before exit (contains error msg or function-specific value)

$db = null;
$retVal = "";

// Create return result web page
function createReturnDiv ($error) {
    global $retVal;
    $retVal = 
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
    return true;
}

function uploadImageFile() {
    global $retVal;

    $errMsg = array(1 => 'php.ini max file size exceeded', 
                2 => 'html form max file size exceeded', 
                3 => 'file upload was only partial', 
                4 => 'no file was attached'); 

    echo "Name: '" . $_FILES["imageFile"]["name"] . "' 1st<br>";
    echo "tmp_name: '" . $_FILES["imageFile"]["tmp_name"] . "' 2nd<br>";
    echo "error: '" . $_FILES["imageFile"]['error'] . "' 3rd<br>";
    echo "is array?: '" . is_array($_FILES['imageFile']['error']) . "' 4th<br>";
    echo "error msg: '" . $errMsg [$_FILES["imageFile"]['error']] . "' <br>";
    echo "size: '" . $_FILES["imageFile"]['size'] . "' <br>";

    $uploadDir = "/srv/www/stg.crosswalk-project.org/_db-app-images/";
    $tmpName = $_FILES["imageFile"]['tmp_name'];

    //header('Content-Type: text/plain; charset=utf-8');

    echo $_FILES;
    echo $_FILES[$imgFormName];
    
    try {
        // Check if undefined, multiple files, or $_FILES corruption attack
        echo "checking!";
        if (!isset($_FILES["imageFile"]['error']) ||
             is_array($_FILES['imageFile']['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }
        switch ($_FILES['imageFile']['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            throw new RuntimeException('No image file was sent.');
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new RuntimeException('Image file exceeds size limit.');
        default:
            throw new RuntimeException('Unknown errors uploading image.');
        }

        // Check filesize
        if ($_FILES[$imgFormName]['size'] > 2000000) {
            throw new RuntimeException('Image file exceeds size limit.');
        }

        // Check MIME type
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        if (false === $ext = array_search (
            $finfo->file ($tmpName),
            array ('jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'),
            true)) {
            throw new RuntimeException('Invalid file format. Image file must be jpg, png, or gif.');
        }
        echo '<br><br>GOT TO HERE<br><br>';

        // Give file unique name (we will store in DB)
        $uniqueName = sprintf ("%s/%s/%s", $uploadDir, sha1_file($tmpName), $ext);

        echo "<br><br>unuqueName: $uniqueName<br><br>";

        if (!move_uploaded_file ($tmpName, $uniqueName)) {
            throw new RuntimeException('Failed to move uploaded image file.');
        }
        $retVal = $uniqueName;
    } catch (RuntimeException $e) {
        $retVal = $e->getMessage();
        return false;
    }
    return true;
}

/*
function addRecordToDb () {
    global $db;
    global $retVal;
    // Insert values into DB
    $cmd = "INSERT INTO xwalk_apps (name, author, author_url, google_play_url, publish_date, num_downloads, price, size, category, version) 
            VALUES ('$name', '$author', '$authorUrl', '$googleUrl', '$publishDate', '$downloads', '$price', '$size', '$category', '$version')";
    $result = $db->query($cmd);
    if (!$result) {
        $retVal = "Error: The application \"{$name}\" could not be added to the xwalk application database. (" . $db->error . ")";
        return false;
    } 
    return true;
}
*/


function getFormValues() {
    global $db;
    global $retVal;

    //insecure way: $name = $_POST['name'];
    $googleUrl = mysqli_real_escape_string($db, $_POST['googleUrl']);
    $name =   mysqli_real_escape_string($db, $_POST['name']);
    $author = mysqli_real_escape_string($db, $_POST['author']);
    $authorUrl = mysqli_real_escape_string($db, $_POST['authorUrl']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $publishDate = mysqli_real_escape_string($db, $_POST['publishDate']);
    $downloads = mysqli_real_escape_string($db, $_POST['downloads']);
    $price = mysqli_real_escape_string($db, $_POST['price']);
    $size = mysqli_real_escape_string($db, $_POST['size']);
   //TBD: arch
    $xdk = mysqli_real_escape_string($db, $_POST['xdk']);
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $version = mysqli_real_escape_string($db, $_POST['version']);
    $notes = mysqli_real_escape_string($db, $_POST['notes']);
    $spam =  mysqli_real_escape_string($db, $_POST['spam']);
    // Validate input
    if (strlen($name) == 0 || strlen($author) == 0 || strlen(email)==0) {
        $retVal = "One of the required form fields is empty. Application submission denied.";
        return false;
    }

    echo "app name: '" . $name . "' 1st<br>";
    echo "app author: '" . $author . "' 2nd<br>";

    return true;
}

function connectToDB () {
    global $retVal;
    global $db;
    $db  = new mysqli('localhost', 'xwalkweb', 'webapps', 'xwalk');
    if ($db->connect_errno) {
        $retVal = "Unable to connect to database. " . $db->connect_error;
        return false;
    }
    return true;
}

/*
// ------ Start --------------
if (!connectToDB ()) {
    createReturnDiv ($retVal);
    echo $retVal;
    exit;
}
if (!getFormValues()) {
    createReturnDiv ($retVal);
    echo $retVal;
    exit;
}
    echo $_FILES['imageFile']['name'] . "<br>";
    echo "tmp_name: '" . $_FILES['imageFile']['tmp_name'] . "' 2nd<br>";

if (!uploadImageFile()) {
    createReturnDiv ($retVal);
    echo $retVal;
    exit;
} else {
    $imgPath = $retVal;
}
echo 'imgPath: $imgPath<br><br>';

createReturnDiv ();
echo $retVal;


if (!addRecordToDb ()) {
    createReturnDiv ($retVal);
} else {
    createReturnDiv ();
}
echo $retVal;

// Close connection
$db->close();
*/
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

