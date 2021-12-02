<?php

session_start();

if (!isset($_SESSION["felhasznalonev"])) {
    header("location: login.php");
} else {
    if ($_SESSION["engedely"] != "admin" && $_SESSION["engedely"] != "modosito") {
        header("location: index.php");
    }
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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <script src="jquery-3.5.1.min.js"></script>
</head>

<body style="background-color:#867c8a">
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on("click", "#submit", function (e) {
            e.preventDefault();

            if ($("#nev").val().length === 0) {
                alert("A név nem lehet üres");
                return;
            }

            if ($("#telepules").val().length === 0) {
                alert("A település nem lehet üres");
                return;
            }

            if ($("#hossz").val() === 0) {
                alert("A hossz nem lehet 0");
                return;
            }

            if ($("#kiterjedes").val() === 0) {
                alert("A kiterjedés nem lehet 0");
                return;
            }

            if ($("#melyseg").val() === 0) {
                alert("A mélység nem lehet 0");
                return;
            }

            if ($("#magassag").val() === 0) {
                alert("A magasság nem lehet 0");
                return;
            }

            $("form").submit();
        });
    });
</script>
<nav class="nav" tabindex="-1">
    <span style="<?php ($_SESSION["mas_felhasznalo"] == 1 ? "color: red; " : "") ?> float: right; margin: 5px 10px;"
          class="name"><?php echo $_SESSION["nev"]; ?></span>
    <div class="container">
        <?php
        function connect_to_mysqli()
        {
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
        while ($row = mysqli_fetch_array($result)) {
            $menuLink = $row['href'];
            $menuNev = $row['description'];
            if ($row["engedely"] == "admin") {
                if($_SESSION["engedely"] == "admin") {
                    echo '<a href="' . $menuLink . '">' . $menuNev . '</a>';
                }
            } else {
                echo '<a href="' . $menuLink . '">' . $menuNev . '</a>';
            }
        }

        if (isset($_POST['submit'])) {

            $update_query = "UPDATE barlang SET nev = '" . $_POST['nev'] . "', hossz = " . $_POST['hossz'] . ",  kiterjedes = " . $_POST['kiterjedes'] . ",  melyseg = " . $_POST['melyseg'] . ",  magassag = " . $_POST['magassag'] . ",  telepules = '" . $_POST['telepules'] . "'";
            $uploaded = is_uploaded_file($_FILES['fenykep']['tmp_name']);

            if ($uploaded) {
                $safe_filename = trim($_FILES['fenykep']['name']);
                $safe_filename = rand() . $safe_filename;
                move_uploaded_file($_FILES['fenykep']['tmp_name'], "images/" . $safe_filename);
                $update_query .= ", fenykep='" . $safe_filename . "'";
            }
            $update_query .= " WHERE id=" . $_GET['id'];
            if (!mysqli_query($conn, $update_query)) {
                echo "Error: " . $update_query . "<br>" . mysqli_error($conn);
            }
        }
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM barlang WHERE id=" . $id;
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
        } else {
            $id = "";
        }
        mysqli_close($conn);

        ?>
    </div>
    <div class="container" style="margin-top:50px">
        <form action="<?php echo "modositas.php?id=" . $id ?>" method="post" enctype="multipart/form-data">
            Név <input type="text" name="nev" id="nev" value="<?php echo $row['nev'] ?>"><br>
            Hossz <input type="number" name="hossz" id="hossz" value="<?php echo $row['hossz'] ?>"><br>
            Kiterjedés <input type="number" name="kiterjedes" id="kiterjedes"
                              value="<?php echo $row['kiterjedes'] ?>"><br>
            Mélység <input type="number" name="melyseg" id="melyseg" value="<?php echo $row['melyseg'] ?>"><br>
            Magasság <input type="number" name="magassag" id="magassag" value="<?php echo $row['magassag'] ?>"><br>
            Település <input type="text" name="telepules" id="telepules" value="<?php echo $row['telepules'] ?>"><br>
            Fénykép <input type="file" name="fenykep"><br>
            <input type="submit" name="submit" id="submit">
        </form>

    </div>

</body>

</html>



