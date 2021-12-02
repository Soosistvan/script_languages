<?php

session_start();

if (!isset($_SESSION["felhasznalonev"])) {
    header("location: login.php");
}

if ($_SESSION["engedely"] != "admin") {
    header("location: index.php");
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
                <th>Törlés</th>
            </tr>
            </thead>

            <?php
            $conn = connect_to_mysqli();
            if (isset($_POST['submit'])) {
                $pic_query = "SELECT fenykep FROM barlang WHERE id=" . $_POST['id'];
                $result = mysqli_query($conn, $pic_query);
                list($file) = mysqli_fetch_row($result);
                $fileimage = "images/" . $file;
                if (is_file($fileimage)) {
                    unlink($fileimage);
                }

                $query = "DELETE FROM barlang WHERE id=" . $_POST['id'];
                if (!mysqli_query($conn, $query)) {
                    echo "Error: " . $query . "<br>" . mysqli_error($conn);
                }
            }
            $query = "SELECT * FROM barlang";
            if (isset($_GET['order'])) {
                $query .= " ORDER BY " . $_GET['order'];
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
                    <td>
                        <form action="torles.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                            <input type="submit" value="Törlés" name="submit">
                        </form>
                    </td>
                </tr>

            <?php }

            ?>
        </table>
    </div>
    <?php

    mysqli_close($conn);
    ?>
</body>

</html>



