<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/min.css">
  <meta name="theme-color" content="#fafafa">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
</head>

<body>
<nav class="nav" tabindex="-1">
<div class="container">
<?php

/*
CREATE TABLE `barlang`.`menu` ( `href` VARCHAR(128) NOT NULL , `description` VARCHAR(128) NOT NULL ) ENGINE = MyISAM;
INSERT INTO `menu` (description, href) VALUES ('Felvitel', 'felvitel.php');
INSERT INTO `menu` (description, href) VALUES ('Listázás', 'index.php');
INSERT INTO `menu` (description, href) VALUES ('Törlés', 'torles.php');
INSERT INTO `menu` (description, href) VALUES ('Módosítás', 'modositas.php');
CREATE TABLE `barlang`.`barlang` ( `id` INT AUTO_INCREMENT PRIMARY KEY , `nev` VARCHAR(128) NOT NULL , `hossz` INT NOT NULL , `kiterjedes` INT NOT NULL , `melyseg` INT NOT NULL , `magassag` INT NOT NULL , `telepules` VARCHAR(128) NOT NULL , `fenykep` VARCHAR(128) NOT NULL ) ENGINE = MyISAM
*/
$conn = mysqli_connect("eu-cdbr-west-03.cleardb.net", "b2f48905b8d51f", "d2c81bd4", "heroku_df0ca9583326004") or die("Connect hiba: ".mysqli_connect_error());
$conn->set_charset("utf8");
$query = "SELECT * FROM menu";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_array($result)){$menuLink = $row['href'];$menuNev = $row['description'];
?>

<a href=<?php echo"'$menuLink'>$menuNev"; ?></a>

<?php }?>
</div>
</body>

</html>





