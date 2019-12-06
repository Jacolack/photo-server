create table photos(
   id INT NOT NULL AUTO_INCREMENT,
   tags VARCHAR(1100) NOT NULL,
   location VARCHAR(1100) NOT NULL,
   fileType CHAR(32) NOT NULL,
   uploaded DATETIME NOT NULL,
   PRIMARY KEY ( id )
);

create table folders(
   id INT NOT NULL AUTO_INCREMENT,
   name CHAR(32) NOT NULL,
   location VARCHAR(1100) NOT NULL,
   PRIMARY KEY ( id )
);



$leftCmd = "df -h | grep /dev/root | awk -F ' ' '{print $4}'";
$totalCmd = "df -h | grep /dev/root | awk -F ' ' '{print $2}'";
$percentCmd = "df | grep /dev/root | awk -F ' ' '{print $5}' | sed 's/%//'";



$res = mysqli_query($mysqli, "SELECT * FROM commentTable WHERE code = '" . $theCode . "'");

// Calculate seconds from offset

echo "<div class='listComments'>"; 
while($row = mysqli_fetch_assoc($res)) {

echo "<div class='container'>"; echo "<p>" . $row['comment'] . "</p>";

$timestamp = (strtotime($row['time']) - (($timezone - 240)*60));

$result = date("g:i a", $timestamp);
$name = $row['name'];
if (empty($name))
{
$name = "Anonymous";
}


echo "<span class='time-right'>" . $result . "</span>"; 
echo "<span class='time-left'>" . $name . "</span>"; echo "</div>";
}
echo "</div>";









$stmt = mysqli_prepare($conn, "INSERT INTO commentTable (name, code, comment, time) VALUES (?, ?, ?, NOW())");
mysqli_stmt_bind_param($stmt, 'sss', $name, $code, $com);
