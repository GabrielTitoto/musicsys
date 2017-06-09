<?php

session_start();
if (!$_SESSION["usuario"]["logado"]) {
    header("Location: login.php");
    exit();
}

if ($admin) {
    if (!$_SESSION["usuario"]["admin"]) {
        header("Location: index.php");
        exit();
    }
}