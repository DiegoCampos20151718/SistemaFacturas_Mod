<?php
include("session.php");

startSession();

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

