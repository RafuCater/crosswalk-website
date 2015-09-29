
<?php

function createReturnDiv ($error) {
    $ret = 
      "<table width=100%>" .
        "<tr>" . 
	  "<td style='text-align:center;width: 80px; padding: 10px'><img src='" .
            (empty($error) ? "/assets/apps/submit-app-success-icon.png" : "/assets/apps/submit-app-fail-icon.png") . "'>" .
          "</td>" .
	  "<td style='padding: 10px; vertical-align:middle;'>" .
            (empty($error) ? 
           "<strong>Application Submitted Successfully</strong><br>" . 
           "Your application will be reviewed and, once accepted, will be added to the " . 
           "<a href='#'>Crosswalk Applications</a> page." : 
           "<strong>Submission Error</strong><br>" . 
           "The application submission failed.  The error returned was:<br><br>" . $error) . 
         "</td>" .
	"</tr>" . 
      "</table>";

    return $ret;
}

$db = new mysqli('localhost', 'xwalkweb', 'webapps', 'xwalk');
if ($db->connect_errno) {
    echo createReturnDiv ("Error: Unable to connect to xwalk database: " . $db->connect_error);
    exit;
}

    //$name = $_POST['name'];
    //$author = $_POST['author'];

    $googleUrl = mysqli_real_escape_string($db, $_POST['googleUrl']);
    $name =   mysqli_real_escape_string($db, $_POST['name']);
//    $imageFile = mysqli_real_escape_string($db, $_POST['imageFile']);
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

    // Validate input
    if (strlen($name) == 0 || strlen($author) == 0 || strlen(email)==0) {
        echo createReturnDiv ("Error: One of the required form fields is empty. Application submission denied.");
        exit;
    }

// Insert values into DB
$cmd = "INSERT INTO xwalk_apps (name, author, author_url, google_play_url, publish_date, num_downloads, price, size, category, version) 
       VALUES ('$name', '$author', '$authorUrl', '$googleUrl', '$publishDate', '$downloads', '$price', '$size', '$category', '$version')";
$result = $db->query($cmd);
if (!$result) {
    echo createReturnDiv ("Error: Application ($name) could not be added to the xwalk application database. " . $db->error);
} else {
    echo createReturnDiv();
}

// Close connection
$db->close();

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
?>


