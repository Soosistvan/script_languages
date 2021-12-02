<?php

session_start();

if (isset($_SESSION["felhasznalonev"])) {
    header("location: index.php");
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

function connect_to_mysqli()
{
    $connect = mysqli_connect("eu-cdbr-west-03.cleardb.net", "b2f48905b8d51f", "d2c81bd4", "heroku_df0ca9583326004");
    if (!$connect) {
        die("Connection failed mysql: " . mysqli_connect_error());
    }
    
    $connect->set_charset("utf8");
    return $connect;
}

if (isset($_POST["submit"])) {
    if (!checkRecaptcha($_POST["token"])) {
        echo "<script>alert('A recaptcha szerint robot vagy.');</script>";
    } else {
        if (empty($_POST["felhasznalonev"]) || empty($_POST["jelszo"])) {
            echo "<script>alert('A felhasználónév vagy a jelszó hiányzik.');</script>";
        } else {
            $conn = connect_to_mysqli();
            $query = 'SELECT * FROM felhasznalok WHERE felhasznalonev = "' . $_POST["felhasznalonev"] . '" AND jelszo = "' . md5($_POST["jelszo"]) . '" LIMIT 1';
            $result = mysqli_query($conn, $query);

            echo mysqli_num_rows($result);

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result);
                $_SESSION["nev"] = $row["nev"];
                $_SESSION["felhasznalonev"] = $row["felhasznalonev"];
                $_SESSION["engedely"] = $row["engedely"];

                if ($_COOKIE["login_ip"] == $_SERVER["REMOTE_ADDR"] && $_COOKIE["login_felhasznalonev"] != $row["felhasznalonev"]) {
                    $_SESSION["mas_felhasznalo"] = 1;
                } else {
                    $_SESSION["mas_felhasznalo"] = 0;
                }
                setcookie("login_ip", $_SERVER['REMOTE_ADDR'], strtotime('+30 days'));
                setcookie("login_felhasznalonev", $row["felhasznalonev"], strtotime('+30 days'));

                header("location: index.php");
            } else {
                echo "<script>alert('Hibás felhasználónév vagy jelszó.');</script>";
            }
        }
    }
}

?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <title>Bejelentkezés</title>
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
<form method="post" action="login.php">
    <span>Bejelentkezés</span>
    <input type="text" name="felhasznalonev" placeholder="Felhasználónév" id="felhasznalonev"/>
    <input type="password" name="jelszo" placeholder="Jelszó" id="jelszo"/>
    <input type="hidden" value="" name="token" id="token"/>
    <input type="submit" value="Belépés" name="submit" id="submit"/>
    <a href="regisztracio.php">Regisztráció</a>
</form>
<script type="text/javascript">
    $(document).ready(function () {
        grecaptcha.ready(function () {
            $(document).on("click", "#submit", function (e) {
                e.preventDefault();

                if ($("#felhasznalonev").val().length === 0) {
                    alert("A felhasználónév üres");
                    return;
                }

                if ($("#jelszo").val().length === 0) {
                    alert("A jelszó üres");
                    return;
                }

                grecaptcha.execute('6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI', {action: 'login'}).then(function (token) {
                    $("#token").val(token);

                    $("form").submit();
                });
            });
        });
    });
</script>
</body>
