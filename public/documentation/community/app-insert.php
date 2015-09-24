
<?php

$db = new mysqli('localhost', 'xwalkweb', 'webapps', 'xwalk');
if ($db->connect_errno) {
    die("Connection failed: " . $db->connect_error);
}
echo "Connect great boys" . "<br>";

//$name = $_POST['name'];
//$author = $_POST['author'];
$name =   mysqli_real_escape_string($db, $_POST['name']);
$author = mysqli_real_escape_string($db, $_POST['author']);
echo "App name: " . $name . ", Author: " . $author . "<br>";
if (strlen($name) == 0 || strlen($author) == 0) {
     echo("<br>Nothing added to DB this time. Empty values<br>");
     exit;
}

$cmd = "INSERT INTO xwalk_apps (name, author) VALUES ('$name', '$author')";
// insert into xwalk_apps (appid,name,author,publish_date,num_downloads,image) values ("1","Sudoku","S-Man Inc.","2015-02-04","10000","sudoku64.jpg");

echo "About to execute cmd: " . $cmd;
$result = $db->query($cmd);
if ($result) {
    echo ("<br>App inserted successfully!");
} else {
	echo "<br>Error: App not inserted. " . $db->error;
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


