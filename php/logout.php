<?php
session_start();
include ("session.php");

// Destruir la sesión
destroySession();


header("Location: ../login.php");
exit();
