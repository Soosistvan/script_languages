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
    <link rel="stylesheet" href="css/filter.css">
    <meta name="theme-color" content="#fafafa">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <script src="jquery-3.5.1.min.js"></script>
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
                <th><a href="#" class="order" data-order="id">ID</a></th>
                <th><a href="#" class="order" data-order="nev">Név</a></th>
                <th><a href="#" class="order" data-order="hossz">Hossz</a></th>
                <th><a href="#" class="order" data-order="kiterjedes">Kiterjedés</a></th>
                <th><a href="#" class="order" data-order="melyseg">Mélység</a></th>
                <th><a href="#" class="order" data-order="magassag">Magasság</a></th>
                <th><a href="#" class="order" data-order="telepules">Település</a></th>
                <th><a href="#" class="order" data-order="fenykep">Fénykép</a></th>
                <?php if ($_SESSION["engedely"] == "admin") { ?>
                    <th>Módosítás</th>
                <?php } ?>
            </tr>
            <tr>
                <td><input type="text" class="filter-input" id="id" placeholder="ID" /> </td>
                <td><input type="text" class="filter-input" id="nev" placeholder="Név" /> </td>
                <td><input type="number" class="filter-input" id="hossz" placeholder="Hossz" /> </td>
                <td><input type="number" class="filter-input" id="kiterjedes" placeholder="Kiterjedés" /> </td>
                <td><input type="number" class="filter-input" id="melyseg" placeholder="Mélység" /> </td>
                <td><input type="number" class="filter-input" id="magassag" placeholder="Magasság" /> </td>
                <td><input type="text" class="filter-input" id="telepules" placeholder="Település" /> </td>
                <td><input type="submit" id="filter" value="Keresés" /> </td>
            </tr>
            </thead>
            <?php
            $conn = connect_to_mysqli();
            $query = "SELECT * FROM barlang WHERE 1 = 1";

            if (isset($_GET['id'])) {
                $query .= " AND nev LIKE '%" . $_GET['nev'] . "%'";
            }

            if (isset($_GET['nev'])) {
                $query .= " AND nev LIKE '%" . $_GET['nev'] . "%'";
            }

            if (isset($_GET['hossz'])) {
                $query .= " AND hossz LIKE '%" . $_GET['hossz'] . "%'";
            }

            if (isset($_GET['kiterjedes'])) {
                $query .= " AND kiterjedes LIKE '%" . $_GET['kiterjedes'] . "%'";
            }

            if (isset($_GET['melyseg'])) {
                $query .= " AND melyseg LIKE '%" . $_GET['melyseg'] . "%'";
            }

            if (isset($_GET['magassag'])) {
                $query .= " AND magassag LIKE '%" . $_GET['magassag'] . "%'";
            }

            if (isset($_GET['telepules'])) {
                $query .= " AND telepules LIKE '%" . $_GET['telepules'] . "%'";
            }

            if (isset($_GET['order'])) {
                $query .= " ORDER BY " . $_GET['order'];
            }
            $query .= " LIMIT 15";
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
                    <?php if ($_SESSION["engedely"] == "modosito" || $_SESSION["engedely"] == "admin") { ?>
                        <td><a href="<?php echo "modositas.php?id=" . $row['id'] ?>">Módosítás</a></td>
                    <?php } ?>
                </tr>

            <?php }
            mysqli_close($conn);
            ?>
        </table>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {

            $(document).on("click", "#filter", function () {
                keresesRendezes("");
            });

            $(document).on("click", ".order", function () {
                keresesRendezes($(this).data("order"));
            });
        });

        function keresesRendezes(order) {
            var first = true;
            var params = "";

            $.each($(".filter-input"), function () {
                if($(this).val() !== "") {
                    if(first) {
                        params += "?" + $(this).attr("id") + "=" + $(this).val();
                        first = false;
                    } else {
                        params += "&" + $(this).attr("id") + "=" + $(this).val();
                    }
                }
            });

            if(order.length > 0) {
                if(first) {
                    params += "?order=" + order;
                } else {
                    params += "&order=" + order;
                }
            }

            console.log(params);

            location.href = "index.php" + params;
        }
    </script>
</body>

</html>



