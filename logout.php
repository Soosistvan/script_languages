<?php

session_start();

if (isset($_SESSION["felhasznalonev"])) {
    session_destroy();
}

header("location: login.php");
