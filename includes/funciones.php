<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    return htmlspecialchars($html ?? '');
}

//Función que revisa si el usuario esta autenticado
function isAuth() : void {

    if(!isset($_SESSION["login"]) || !isset($_SESSION["admin"]) == "0") {
        header("Location: /");
        exit();
    }

}

//Función que revisa si el admin esta autenticado
function isAdmin() : void {

    if(!isset($_SESSION["login"]) || !isset($_SESSION["admin"])) {
        header("Location: /");
        exit(); 
    }

}

//Función que revisa si es el ultimo en un arreglo
function esUltimo($actual, $siguiente) {

    if($actual !== $siguiente) {
        return true;
    }

    return false;

}