<?php

session_start();

if (isset($_SESSION["felhasznalonev"])) {
    header("location: index.php");
}

function connect_to_mysqli()
{
    $connect = mysqli_connect("eu-cdbr-west-03.cleardb.net", "b2f48905b8d51f", "d2c81bd4", "heroku_df0ca9583326004");
    if (!$connect) {
        die("Connection failed mysql: " . mysqli_connect_error());
    }
    $connect->set_charset("utf8");
    return $connect;
}

function checkRecaptcha($token)
{
    $secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array('secret' => $secret, 'response' => $token);
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);
    $responseKeys = json_decode($response, true);
    return $responseKeys["success"];
}

if (isset($_POST["submit"])) {
    if (!checkRecaptcha($_POST["token"])) {
        echo "<script>alert('A recaptcha szerint robot vagy.');</script>";
    } else {
        if (empty($_POST["felhasznalonev"])) {
            echo "<script>alert('A felhasználónév hiányzik.');</script>";
        } else if (empty($_POST["nev"])) {
            echo "<script>alert('A név hiányzik.');</script>";
        } else if (empty($_POST["jelszo"])) {
            echo "<script>alert('A jelszó hiányzik.');</script>";
        } else if (empty($_POST["jelszo_ujra"])) {
            echo "<script>alert('A jelszó megerősítés hiányzik.');</script>";
        } else if ($_POST["jelszo"] != $_POST["jelszo_ujra"]) {
            echo "<script>alert('A két jelszó nem egyezik.');</script>";
        } else {
            $conn = connect_to_mysqli();
            $query = 'SELECT * FROM felhasznalok WHERE felhasznalonev = "' . $_POST["felhasznalonev"] . '" LIMIT 1';
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "<script>alert('A felhasználónév foglalt.');</script>";
            } else {
                $query = 'INSERT INTO felhasznalok (felhasznalonev, nev, jelszo, engedely) VALUES ("' . $_POST["felhasznalonev"] . '", "' . $_POST["nev"] . '", "' . md5($_POST["jelszo"]) . '", "felhasznalo")';
                mysqli_query($conn, $query);
                echo "<script>alert('Sikeres regisztráció.'); location.href = 'login.php'; </script>";
            }
        }
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
    <link rel="stylesheet" href="css/login.css">
    <meta name="theme-color" content="#fafafa">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <script src="https://www.google.com/recaptcha/api.js?render=6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></script>
    <script>
        grecaptcha.ready(() => {
            grecaptcha.render('html_element', {
                'sitekey': '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
            });
        });
    </script>
</head>

<body style="background-color:#867c8a">
<script type="text/javascript">
    function submit_form() {

    }
</script>

<form method="post" action="regisztracio.php">
    <span>Regisztráció</span>
    <input type="text" name="nev" placeholder="Név"/>
    <input type="text" name="felhasznalonev" placeholder="Felhasználónév"/>
    <input type="password" name="jelszo" placeholder="Jelszó"/>
    <input type="password" name="jelszo_ujra" placeholder="Jelszó újra"/>
    <input type="submit" value="Regisztráció" name="submit"/>
    <a href="login.php">Bejelentkezés</a>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        grecaptcha.ready(function () {
            $(document).on("click", "#submit", function (e) {
                e.preventDefault();

                if ($("#nev").val().length === 0) {
                    alert("A név üres");
                    return;
                }

                if ($("#felhasznalonev").val().length === 0) {
                    alert("A felhasználónév üres");
                    return;
                }

                if ($("#jelszo").val().length === 0) {
                    alert("A jelszó üres");
                    return;
                }

                if ($("#jelszo_ujra").val().length === 0) {
                    alert("A jelszó újra üres");
                    return;
                }

                if ($("#jelszo").val() !== $("#jelszo_ujra").val()) {
                    alert("A két jelszó nem egyezik.");
                    return;
                }

                grecaptcha.execute('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', {action: 'register'}).then(function (token) {

                    $("#token").val(token);

                    $("form").submit();
                });
            });
        });
    });
</script>
</body>
