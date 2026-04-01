php
<?php
// funciones/validacion.php

function limpiarDato($dato) {
    $dato = trim($dato);
    $dato = stripslashes($dato);
    $dato = htmlspecialchars($dato);
    return $dato;
}

function validarUsuario($usuario) {
    if (empty($usuario)) return "El usuario es obligatorio";
    if (strlen($usuario) < 4) return "Mínimo 4 caracteres";
    if (!preg_match('/^[a-zA-Z0-9]+$/', $usuario)) return "Solo letras y números";
    return true;
}

function validarEmail($email) {
    if (empty($email)) return "El email es obligatorio";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return "Email inválido";
    return true;
}

function validarPassword($password) {
    if (empty($password)) return "La contraseña es obligatoria";
    if (strlen($password) < 6) return "Mínimo 6 caracteres";
    return true;
}
?>
