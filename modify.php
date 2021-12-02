<?php

session_start();

if(!isset($_SESSION["felhasznalonev"])) {
    header("location: login.php");
}

?>

<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/min.css">
  <meta name="theme-color" content="#fafafa">
</head>

<body style="background-color:#867c8a">
<nav class="nav" tabindex="-1">
    <span style="<?php ($_SESSION["mas_felhasznalo"] == 1 ? "color: red; " : "") ?> float: right; margin: 5px 10px;" class="name"><?php echo $_SESSION["nev"]; ?></span>
<div class="container">
<?php
function connect_to_mysqli(){
	$connect = mysqli_connect("eu-cdbr-west-03.cleardb.net", "b2f48905b8d51f", "d2c81bd4", "heroku_df0ca9583326004");
	if (!$connect) {
		  die("Connection failed mysql: " . mysqli_connect_error());
	}
	$connect->set_charset("utf8");
	return $connect;	
}


/*
CREATE TABLE `barlang`.`menu` ( `href` VARCHAR(128) NOT NULL , `description` VARCHAR(128) NOT NULL ) ENGINE = MyISAM;
INSERT INTO `menu` (description, href) VALUES ('Felvitel', 'felvitel.php');
INSERT INTO `menu` (description, href) VALUES ('Listázás', 'index.php');
INSERT INTO `menu` (description, href) VALUES ('Törlés', 'torles.php');
INSERT INTO `menu` (description, href) VALUES ('Módosítás', 'modositas.php');
CREATE TABLE `barlang`.`barlang` ( `id` INT AUTO_INCREMENT PRIMARY KEY , `nev` VARCHAR(128) NOT NULL , `hossz` INT NOT NULL , `kiterjedes` INT NOT NULL , `melyseg` INT NOT NULL , `magassag` INT NOT NULL , `telepules` VARCHAR(128) NOT NULL , `fenykep` VARCHAR(128) NOT NULL ) ENGINE = MyISAM
*/
$conn = connect_to_mysqli();
$query = "SELECT * FROM menu";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)){
    if($row["csak_admin"] == "0" || ($row["csak_admin"] == "1" && $_SESSION["engedely"] == "admin")) {

        $menuLink = $row['href'];
        $menuNev = $row['description'];
        echo '<a href="' . $menuLink . '">' . $menuNev . '</a>';
    }
}
mysqli_close($conn);
?>

</div>
<div class="container" style="margin-top:50px">
<form action="felvitel.php" method="post" enctype="multipart/form-data">
Név <input type="text" name="nev"><br>
Hossz <input type="number" name="hossz"><br>
Kiterjedés <input type="number" name="kiterjedes"><br>
Mélység <input type="number" name="melyseg"><br>
Magasság <input type="number" name="magassag"><br>
Település <input type="text" name="telepules"><br>
Fénykép <input type="file" name="fenykep"><br>
<input type="submit" name="submit">
</form>

</div>
<?php
if(isset($_POST['submit'])){ 
$conn = connect_to_mysqli();

$safe_filename = trim($_FILES['fenykep']['name']); 
$safe_filename = rand().$safe_filename; 
move_uploaded_file($_FILES['fenykep']['tmp_name'], "images/".$safe_filename); 
$query = "INSERT INTO barlang (nev, hossz, kiterjedes, melyseg, magassag, telepules, fenykep) VALUES 
('".$_POST['nev']."',".$_POST['hossz'].",".$_POST['kiterjedes'].",".$_POST['melyseg'].",".$_POST['magassag'].",'".$_POST['telepules']."','".$safe_filename."')"; 
if (!mysqli_query($conn, $query)) {
 echo "Error: " . $query. "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
}
?>
</body>

</html>



