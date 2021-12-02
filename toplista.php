<?php

session_start();

if (!isset($_SESSION["felhasznalonev"])) {
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
    <link rel="stylesheet" href="css/toplista.css">
    <meta name="theme-color" content="#fafafa">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
</head>

<body style="background-color:#867c8a">
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
        mysqli_close($conn);
        ?>
    </div>
    <div class="container" style="margin-top:50px">
        <form action="toplista.php" method="get" class="szures">
            <input type="text" placeholder="Darabszám (N)" name="n_darab" id="n_darab"
                   value="<?php echo isset($_GET["n_darab"]) ? $_GET["n_darab"] : "" ?>"/>
            <select id="n_select" name="n_select">
                <option value="0">Válassz</option>
                <option <?php echo (isset($_GET["n_select"]) && $_GET["n_select"] == "1") ? "selected" : "" ?>
                        value="1">Legmélyebb
                </option>
                <option <?php echo (isset($_GET["n_select"]) && $_GET["n_select"] == "2") ? "selected" : "" ?>
                        value="2">Leghosszabb
                </option>
                <option <?php echo (isset($_GET["n_select"]) && $_GET["n_select"] == "3") ? "selected" : "" ?>
                        value="3">Legkiterjedtebb
                </option>
            </select>
            <input type="submit" id="n_keres" value="Keresés"/>
        </form>
        <table class="table">
            <thead>
            <tr>
                <th><a href="index.php?order=id">ID</a></th>
                <th><a href="index.php?order=nev">Név</a></th>
                <th><a href="index.php?order=hossz">Hossz</a></th>
                <th><a href="index.php?order=kiterjedes">Kiterjedés</a></th>
                <th><a href="index.php?order=melyseg">Mélység</a></th>
                <th><a href="index.php?order=magassag">Magasság</a></th>
                <th><a href="index.php?order=telepules">Település</a></th>
                <th><a href="index.php?order=fenykep">Fénykép</a></th>
            </tr>
            </thead>
            <?php
            $conn = connect_to_mysqli();
            $query = "SELECT * FROM barlang";
            if (!empty($_GET['n_select'])) {
                if ($_GET["n_select"] == "1") {
                    $query .= " ORDER BY melyseg DESC";
                } else if ($_GET["n_select"] == "2") {
                    $query .= " ORDER BY hossz DESC";
                } else if ($_GET["n_select"] == "3") {
                    $query .= " ORDER BY " . $_GET['kiterjedes'] . " DESC";
                }
            }

            if (!empty($_GET["n_darab"])) {
                $query .= ' LIMIT ' . $_GET["n_darab"];
            }

            $result = mysqli_query($conn, $query);
            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['nev']; ?></td>
                    <td><?php echo $row['hossz']; ?></td>
                    <td><?php echo $row['kiterjedes']; ?></td>
                    <td><?php echo $row['melyseg']; ?></td>
                    <td><?php echo $row['magassag']; ?></td>
                    <td><?php echo $row['telepules']; ?></td>
                    <td><a href="<?php echo "images/" . $row['fenykep'] ?>"><?php echo $row['fenykep'] ?></a></td>
                </tr>

            <?php }
            mysqli_close($conn);
            ?>
        </table>
    </div>
</body>

</html>



