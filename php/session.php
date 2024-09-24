<?php
// Define el tiempo máximo de inactividad en segundos (e.g., 1800 segundos = 30 minutos)
define('MAX_INACTIVITY_TIME', 1800);

function startSession() {
    if (!isset($_SESSION)) {
        session_start();
    }
    
   
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > MAX_INACTIVITY_TIME)) {
        
        destroySession();
        header("Location: login.php");
        exit;
    } else {
        
        $_SESSION['last_activity'] = time();
    }
}

function destroySession() {
    session_unset();
    session_destroy();
}

function isLoggedIn() {
    return isset($_SESSION['id']);
}

function isUsuario() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === '1';
}

function isAdmin() {
    return isset($_SESSION['rol']) && $_SESSION['rol'] === '2';
}

